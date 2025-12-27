<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Mail\WelcomeEmail;
use App\Models\LoginActivity;
use App\Models\UserService;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Auth\Access\AuthorizationException;

class AuthController extends Controller
{
    /**
     * Clean up and validate redirect URIs
     * Fixes common typos and ensures valid format
     *
     * @param  string  $redirectUri
     * @return string
     */
    private function cleanRedirectUri(string $redirectUri)
    {
        // Fix common typos in redirect URIs
        if (strpos($redirectUri, '/callack') !== false) {
            $redirectUri = str_replace('/callack', '/callback', $redirectUri);
            Log::info("cleanRedirectUri: Fixed 'callack' typo in redirect_uri", [
                "original" => $redirectUri,
                "fixed" => str_replace('/callack', '/callback', $redirectUri)
            ]);
        }

        // Remove any newlines that might be in the URI
        $redirectUri = trim(preg_replace('/\s+/', '', $redirectUri));

        return $redirectUri;
    }

    /**
     * Show the login form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        // Check if user is already logged in and redirect accordingly
        if (Auth::check()) {
            // Get the necessary query parameters
            $clientId = $request->query("client_id");
            $redirectUri = $request->query("redirect_uri");

            // Check for malformed URL with second question mark instead of ampersand
            if ($clientId && strpos($clientId, '?redirect_uri=') !== false) {
                // Extract the real client_id and redirect_uri
                $parts = explode('?redirect_uri=', $clientId);
                $clientId = $parts[0];
                $redirectUri = urldecode($parts[1]);

                Log::info("showLoginForm: Fixed malformed URL for logged in user", [
                    "corrected_client_id" => $clientId,
                    "corrected_redirect_uri" => $redirectUri
                ]);
            }

            // Clean up redirect URI if present
            if ($redirectUri) {
                $redirectUri = $this->cleanRedirectUri($redirectUri);
            }

            // If either parameter is missing, fall back to session values
            if (!$clientId) {
                $clientId = session("client_id");
            }
            if (!$redirectUri) {
                $redirectUri = session("redirect_uri");
            }

            $directLogin = session("direct_login") ?? (!$clientId && !$redirectUri);

            Log::info("showLoginForm: User already logged in, redirecting", [
                "user_id" => Auth::id(),
                "direct_login" => $directLogin,
                "client_id" => $clientId,
                "redirect_uri" => $redirectUri
            ]);

            if ($directLogin) {
                // Direct login to Ordnary account
                return redirect()->route("account.profile");
            } else if ($clientId && $redirectUri) {
                // SSO login with client parameters
                $client = Client::where("client_id", $clientId)->first();

                if (!$client) {
                    Log::error("showLoginForm: Invalid client_id for logged in user", [
                        "client_id" => $clientId,
                    ]);
                    return redirect()->route("account.profile");
                }

                // Verify the redirect URI is allowed
                $allowedRedirectUris = $this->parseRedirectUris($client->redirect_uris);
                $normalizedAllowedUris = $this->normalizeRedirectUris($allowedRedirectUris, "showLoginForm");

                if (!in_array($redirectUri, $normalizedAllowedUris)) {
                    Log::error("showLoginForm: Redirect URI not allowed for logged in user", [
                        "redirect_uri" => $redirectUri,
                        "allowed_redirect_uris" => $allowedRedirectUris
                    ]);
                    return redirect()->route("account.profile");
                }

                $token = $this->generateToken(Auth::user(), $clientId);

                Log::info("showLoginForm: Generated token for already logged in user", [
                    "user_id" => Auth::id(),
                    "token" => $token,
                ]);

                $targetUrl = $redirectUri . "?token=" . $token;
                return redirect()->away($targetUrl);
            }

            // Fallback redirect to profile page
            return redirect()->route("account.profile");
        }

        // Log the incoming query parameters and full URL
        $clientId = $request->query("client_id");
        $redirectUri = $request->query("redirect_uri");
        $fullUrl = $request->fullUrl();

        Log::debug("showLoginForm: Received query parameters", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "full_url" => $fullUrl
        ]);

        // Check for malformed URL with second question mark instead of ampersand
        if ($clientId && strpos($clientId, '?redirect_uri=') !== false) {
            // Extract the real client_id and redirect_uri
            $parts = explode('?redirect_uri=', $clientId);
            $clientId = $parts[0];
            $redirectUri = urldecode($parts[1]);

            Log::info("showLoginForm: Fixed malformed URL", [
                "corrected_client_id" => $clientId,
                "corrected_redirect_uri" => $redirectUri
            ]);
        }

        // Clean up redirect URI
        if ($redirectUri) {
            $redirectUri = $this->cleanRedirectUri($redirectUri);
        }

        // Check if this is a direct login (without client parameters)
        if (!$clientId || !$redirectUri) {
            Log::info("showLoginForm: Direct login attempt (no client parameters)", [
                "client_id" => $clientId,
                "redirect_uri" => $redirectUri
            ]);
            // Set a flag in the session to indicate this is a direct login
            session(["direct_login" => true]);

            return view("auth.login", [
                "client_name" => "Ordnary Account",
                "recaptcha_site_key" => config('services.recaptcha.site_key'),
                "direct_login" => true
            ]);
        }

        // Verify the client exists and the redirect URI is allowed
        $client = Client::where("client_id", $clientId)->first();
        if (!$client) {
            Log::error("showLoginForm: Invalid client_id", [
                "client_id" => $clientId,
            ]);
            return view("error", [
                "message" => "Invalid client_id.",
            ]);
        }

        // Check if the redirect URI is in the list of allowed URIs for this client
        $allowedRedirectUris = $this->parseRedirectUris($client->redirect_uris);

        // Normalize the allowed URIs to handle common typos
        $normalizedAllowedUris = $this->normalizeRedirectUris($allowedRedirectUris, "showLoginForm");

        if (!in_array($redirectUri, $normalizedAllowedUris)) {
            Log::error("showLoginForm: Redirect URI not allowed", [
                "redirect_uri" => $redirectUri,
                "allowed_redirect_uris" => $allowedRedirectUris,
                "normalized_allowed_uris" => $normalizedAllowedUris
            ]);
            return view("error", [
                "message" => "Redirect URI not allowed.",
            ]);
        }

        // Store client_id and redirect_uri in session for use after login
        session(["client_id" => $clientId, "redirect_uri" => $redirectUri]);
        // Store client name for warning alert
        session(["client_name" => $client->name]);
        // Explicitly make sure direct_login is false for SSO
        session(["direct_login" => false]);

        Log::info("showLoginForm: Stored SSO parameters in session", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "client_name" => $client->name,
            "session_id" => session()->getId(),
            "direct_login" => false
        ]);

        return view("auth.login", [
            "client_name" => $client->name,
            "recaptcha_site_key" => config('services.recaptcha.site_key')
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required",
            // Remove the g-recaptcha-response required validation
        ]);

        // Check for recaptcha response manually
        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (empty($recaptchaResponse)) {
            Log::warning("login: Missing reCAPTCHA response", [
                "email" => $request->email
            ]);

            // Check if this is a direct login
            if (session("direct_login")) {
                return redirect()->route("login")->withErrors([
                    "recaptcha" => "Security verification failed. Please try again."
                ]);
            }

            $clientId = session("client_id");
            $redirectUri = session("redirect_uri");
            $redirectUrl = route("login", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            return redirect($redirectUrl)->withErrors([
                "recaptcha" => "Security verification failed. Please try again."
            ]);
        }

        // Verify reCAPTCHA
        $recaptchaResult = $this->verifyRecaptcha($recaptchaResponse);

        if (!$recaptchaResult['success']) {
            Log::warning("login: reCAPTCHA verification failed", [
                "email" => $request->email,
                "recaptcha_errors" => $recaptchaResult['error-codes'] ?? 'Unknown error',
                "score" => $recaptchaResult['score'] ?? 'N/A'
            ]);

            // Check if this is a direct login
            if (session("direct_login")) {
                return redirect()->route("login")->withErrors([
                    "recaptcha" => "Security verification failed. Please try again."
                ]);
            }

            $clientId = session("client_id");
            $redirectUri = session("redirect_uri");
            $redirectUrl = route("login", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            return redirect($redirectUrl)->withErrors([
                "recaptcha" => "Security verification failed. Please try again."
            ]);
        }

        // Check score for invisible reCAPTCHA v3 (if score is too low, reject the login)
        if (isset($recaptchaResult['score']) && $recaptchaResult['score'] < 0.5) {
            Log::warning("login: reCAPTCHA score too low", [
                "email" => $request->email,
                "score" => $recaptchaResult['score']
            ]);

            // Check if this is a direct login
            if (session("direct_login")) {
                return redirect()->route("login")->withErrors([
                    "recaptcha" => "Security verification failed. Please try again later."
                ]);
            }

            $clientId = session("client_id");
            $redirectUri = session("redirect_uri");
            $redirectUrl = route("login", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            return redirect($redirectUrl)->withErrors([
                "recaptcha" => "Security verification failed. Please try again later."
            ]);
        }

        // Get session variables or input values as fallback
        $clientId = session("client_id", $request->input('client_id'));
        $redirectUri = session("redirect_uri", $request->input('redirect_uri'));

        // Determine if this is a direct login based on presence of SSO parameters
        $directLogin = session("direct_login", !($clientId && $redirectUri));

        // If values came from input and not session, store them in session for later
        if (!session("client_id") && $request->input('client_id')) {
            session(["client_id" => $clientId]);
        }
        if (!session("redirect_uri") && $request->input('redirect_uri')) {
            session(["redirect_uri" => $redirectUri]);
        }

        Log::debug("login: Retrieved parameters", [
            "direct_login" => $directLogin,
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "session_data" => session()->all(),
            "request_data" => $request->only(['client_id', 'redirect_uri'])
        ]);

        try {
            if (Auth::attempt($request->only("email", "password"))) {
                $user = Auth::user();

                // Check if user is suspended
                if ($user->isSuspended()) {
                    // Store user info for banned page before logout
                    session([
                        'suspended_user_id' => $user->id,
                        'suspended_user_timeout' => now()->addHours(24)
                    ]);
                    
                    Auth::logout();
                    Log::warning("login: Suspended user attempted to login", [
                        "user_id" => $user->id,
                        "email" => $user->email,
                        "suspended_at" => $user->suspended_at,
                        "suspension_reason" => $user->suspension_reason
                    ]);

                    return redirect()->route('banned');
                }

                // Check if email is verified
                if (!$user->hasVerifiedEmail()) {
                    Auth::logout();

                    return redirect()->route('login')
                        ->withInput($request->only('email', 'remember'))
                        ->withErrors([
                                'email' => 'You need to verify your email address before logging in. 
                                        <a href="' . route('verification.notice') . '" class="text-blue-500 hover:underline">Verify email</a>'
                            ]);
                }

                Log::info("login: Authentication successful", ["email" => $request->email]);

                // Check if 2FA is enabled for this user
                if ($user->hasTwoFactorEnabled()) {
                    Log::info("login: 2FA is enabled, redirecting to verification", [
                        "user_id" => $user->id,
                        "client_id" => $clientId,
                        "redirect_uri" => $redirectUri,
                        "direct_login" => $directLogin
                    ]);

                    // Log the user out temporarily while we verify 2FA
                    Auth::logout();

                    // Store user ID and SSO parameters in session for 2FA verification
                    session([
                        'auth.2fa.user_id' => $user->id,
                        'auth.2fa.direct_login' => $directLogin,
                        'auth.2fa.client_id' => $clientId,
                        'auth.2fa.redirect_uri' => $redirectUri
                    ]);

                    return redirect()->route('2fa');
                }

                $request->session()->regenerate();

                // Mark this as a new login to clear any UI preferences
                session(['new_login' => true]);

                // Log the login activity
                LoginActivity::logLogin($user, $request);

                // Log parameters before redirect
                Log::info("login: About to handle redirect", [
                    "user_id" => $user->id,
                    "direct_login" => $directLogin,
                    "client_id" => $clientId,
                    "redirect_uri" => $redirectUri,
                    "session_client_id" => session("client_id"),
                    "session_redirect_uri" => session("redirect_uri"),
                    "session_direct_login" => session("direct_login")
                ]);

                // Handle redirect based on session parameters
                return $this->handleLoginRedirect($user, $directLogin, $clientId, $redirectUri);
            }

            $emailExists = User::where("email", $request->email)->exists();

            // Check if this is a direct login
            if ($directLogin) {
                if (!$emailExists) {
                    Log::warning("login: Email not found (direct login)", ["email" => $request->email]);
                    return redirect()->route("login")->withErrors([
                        "email" => "Email address not found.",
                    ]);
                } else {
                    Log::warning("login: Incorrect password for email (direct login)", ["email" => $request->email]);
                    return redirect()->route("login")->withErrors([
                        "password" => "Password is incorrect. Please try again.",
                    ]);
                }
            }

            if (!$clientId || !$redirectUri) {
                Log::error("login: Session data missing for redirect", [
                    "client_id" => $clientId,
                    "redirect_uri" => $redirectUri,
                ]);
                return redirect()->route("login")->withErrors([
                    "error" => "Session data missing. Please try again.",
                ]);
            }

            $redirectUrl = route("login", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            if (!$emailExists) {
                Log::warning("login: Email not found", ["email" => $request->email]);
                return redirect($redirectUrl)->withErrors([
                    "email" => "Email address not found.",
                ]);
            } else {
                Log::warning("login: Incorrect password for email", ["email" => $request->email]);
                return redirect($redirectUrl)->withErrors([
                    "password" => "Password is incorrect. Please try again.",
                ]);
            }
        } catch (\Exception $e) {
            Log::error("login: Exception occurred during login", [
                "exception" => $e->getMessage(),
                "stack_trace" => $e->getTraceAsString(),
            ]);
            return redirect()->route("login")->withErrors([
                "error" => "An error occurred while logging in. Please try again later.",
            ]);
        }
    }

    /**
     * Verify reCAPTCHA response with Google's API
     * Updated to handle invisible reCAPTCHA v3
     *
     * @param string $recaptchaResponse
     * @return array
     */
    private function verifyRecaptcha($recaptchaResponse)
    {
        if (empty($recaptchaResponse)) {
            return ['success' => false, 'error-codes' => ['missing-input-response']];
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $recaptchaResponse,
            'remoteip' => request()->ip()
        ]);

        Log::debug("verifyRecaptcha: Verification response", [
            'response' => $response->json()
        ]);

        return $response->json();
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $userId = Auth::id(); // Save the ID before logging out

        // Check if this was a direct login (not SSO) or if forced to skip SSO
        $isDirectLogin = session('direct_login', false);
        $skipSSO = $request->query('skip_sso', false) || $isDirectLogin;

        Log::info("logout: User logging out", [
            "user_id" => $userId,
            "is_direct_login" => $isDirectLogin,
            "skip_sso" => $skipSSO
        ]);

        // Get the active services for this user (only if not a direct login and not skipping SSO)
        $activeServices = [];
        if ($userId && !$skipSSO) {
            $activeServices = UserService::where('user_id', $userId)
                ->where('status', 'active')
                ->with('client:client_id,name,logout_url')
                ->get();

            Log::info("logout: Found active services for user", [
                "user_id" => $userId,
                "service_count" => $activeServices->count()
            ]);
        } elseif ($skipSSO) {
            Log::info("logout: Skipping SSO logout (direct login or forced skip)", [
                "user_id" => $userId,
                "is_direct_login" => $isDirectLogin,
                "skip_sso_param" => $request->query('skip_sso', false)
            ]);
        }

        // Log the user out from the current application
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Process Single Logout for connected services (only if not skipping SSO)
        $logoutRedirects = [];

        foreach ($activeServices as $service) {
            // Only include services with a logout URL
            if ($service->client && !empty($service->client->logout_url)) {
                $logoutRedirects[] = $service->client->logout_url;

                // Back-channel logout notification (server-to-server)
                try {
                    // Only send webhook if webhook_url is not empty
                    if (!empty($service->client->webhook_url)) {
                        $response = Http::post($service->client->webhook_url, [
                            'event' => 'user_logout',
                            'user_id' => $userId,
                            'service_id' => $service->client_id,
                            'timestamp' => now()->toIso8601String()
                        ]);

                        Log::info("logout: Back-channel logout notification sent", [
                            "user_id" => $userId,
                            "service_id" => $service->client_id,
                            "response_status" => $response->status()
                        ]);
                    } else {
                        Log::info("logout: Skipping back-channel logout notification (no webhook URL)", [
                            "user_id" => $userId,
                            "service_id" => $service->client_id
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error("logout: Back-channel logout notification failed", [
                        "user_id" => $userId,
                        "service_id" => $service->client_id,
                        "error" => $e->getMessage()
                    ]);
                }
            }
        }

        // Store logout redirects in session to process them one by one
        if (!empty($logoutRedirects)) {
            session(['logout_redirects' => $logoutRedirects]);
            return redirect()->route('logout.process');
        }

        return redirect("/");
    }

    /**
     * Process logout redirects one by one for front-channel logout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processLogout(Request $request)
    {
        $logoutRedirects = session('logout_redirects', []);

        if (empty($logoutRedirects)) {
            return redirect('/');
        }

        // Get the next logout URL and remove it from the session
        $nextLogoutUrl = array_shift($logoutRedirects);
        session(['logout_redirects' => $logoutRedirects]);

        // Add a return URL parameter to come back after logout
        $returnUrl = route('logout.process');
        $redirectUrl = $nextLogoutUrl . (strpos($nextLogoutUrl, '?') === false ? '?' : '&') . 'return_to=' . urlencode($returnUrl);

        Log::info("processLogout: Redirecting to service logout URL", [
            "logout_url" => $nextLogoutUrl,
            "redirect_url" => $redirectUrl,
            "remaining_redirects" => count($logoutRedirects)
        ]);

        return redirect()->away($redirectUrl);
    }

    /**
     * Validate a token sent by a client application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateToken(Request $request)
    {
        $request->validate([
            "token" => "required|string",
            "client_id" => "required|string",
            "client_secret" => "required|string",
        ]);

        Log::info("validateToken: Token validation started", [
            "token" => $request->token,
            "client_id" => $request->client_id,
        ]);

        // Verify client credentials
        $client = Client::where("client_id", $request->client_id)
            ->where("client_secret", $request->client_secret)
            ->first();

        if (!$client) {
            Log::error("validateToken: Invalid client credentials", [
                "client_id" => $request->client_id,
            ]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid client credentials.",
                ],
                401
            );
        }

        // Zoek de user session met de gehashte token
        $userSession = UserSession::where("token", hash('sha256', $request->token))
            ->where("client_id", $request->client_id)
            ->first();

        if ($userSession && $userSession->expires_at <= now()) {
            Log::warning("validateToken: Token has expired", [
                "token" => $request->token,
                "expires_at" => $userSession->expires_at,
            ]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Token has expired.",
                ],
                419
            );
        }

        if (!$userSession) {
            Log::warning("validateToken: Invalid or expired token", [
                "token" => $request->token,
                "client_id" => $request->client_id,
            ]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Invalid or expired token.",
                ],
                401
            );
        }

        // Get the user data
        $user = User::find($userSession->user_id);
        if (!$user) {
            Log::error("validateToken: User not found for token", [
                "token" => $request->token,
            ]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "User not found.",
                ],
                404
            );
        }

        // Return user data
        Log::info("validateToken: Token validated successfully", [
            "user_id" => $user->id,
        ]);
        return response()->json([
            "success" => true,
            "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "is_admin" => $user->is_admin,
                ],
        ]);
    }

    /**
     * Show 2FA form after login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show2FAForm(Request $request)
    {
        if (!session()->has('auth.2fa.user_id')) {
            Log::warning("show2FAForm: No user ID in session");
            return redirect()->route('login');
        }

        $userId = session('auth.2fa.user_id');
        $user = User::find($userId);

        if (!$user) {
            Log::warning("show2FAForm: User not found", [
                "user_id" => $userId
            ]);
            session()->forget([
                'auth.2fa.user_id',
                'auth.2fa.direct_login',
                'auth.2fa.client_id',
                'auth.2fa.redirect_uri'
            ]);
            return redirect()->route('login');
        }

        // Get client name for display purposes
        $clientName = "Ordnary Account";
        $clientId = session('auth.2fa.client_id');
        $directLogin = session('auth.2fa.direct_login', false);
        $redirectUri = session('auth.2fa.redirect_uri');

        if ($clientId) {
            $client = Client::where("client_id", $clientId)->first();
            if ($client) {
                $clientName = $client->name;
            }
        }

        Log::info("show2FAForm: Showing 2FA form", [
            "user_id" => $userId,
            "client_name" => $clientName,
            "direct_login" => $directLogin,
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri
        ]);

        return view('auth.2fa', [
            'client_name' => $clientName,
            'email' => $user->email,
            'direct_login' => $directLogin,
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'recaptcha_site_key' => config('services.recaptcha.site_key')
        ]);
    }

    /**
     * Verify 2FA code after login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if (!session()->has('auth.2fa.user_id')) {
            Log::warning("verify2FA: No user ID in session");
            return redirect()->route('login');
        }

        $userId = session('auth.2fa.user_id');
        $user = User::find($userId);

        if (!$user) {
            Log::warning("verify2FA: User not found", [
                "user_id" => $userId
            ]);
            return redirect()->route('login');
        }

        // Verify the 2FA code
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (!$valid) {
            Log::warning("verify2FA: Invalid code", [
                "user_id" => $userId
            ]);
            return redirect()->route('2fa')
                ->withErrors(['code' => 'The verification code is invalid.']);
        }

        // Get SSO parameters from session
        $directLogin = session('auth.2fa.direct_login', false);
        $clientId = session('auth.2fa.client_id');
        $redirectUri = session('auth.2fa.redirect_uri');

        Log::info("verify2FA: Code verified successfully", [
            "user_id" => $userId,
            "direct_login" => $directLogin,
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri
        ]);

        // Clear 2FA session data
        session()->forget([
            'auth.2fa.user_id',
            'auth.2fa.direct_login',
            'auth.2fa.client_id',
            'auth.2fa.redirect_uri'
        ]);

        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();

        // Mark this as a new login to clear any UI preferences
        session(['new_login' => true]);

        // Log the login activity
        LoginActivity::logLogin($user, $request);

        // Handle redirect based on session parameters
        return $this->handleLoginRedirect($user, $directLogin, $clientId, $redirectUri);
    }

    /**
     * Show recovery code form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRecoveryForm(Request $request)
    {
        if (!session()->has('auth.2fa.user_id')) {
            Log::warning("showRecoveryForm: No user ID in session");
            return redirect()->route('login');
        }

        $userId = session('auth.2fa.user_id');
        $user = User::find($userId);

        if (!$user) {
            Log::warning("showRecoveryForm: User not found", [
                "user_id" => $userId
            ]);
            return redirect()->route('login');
        }

        // Get client name for display purposes
        $clientName = "Ordnary Account";
        $clientId = session('auth.2fa.client_id');
        $directLogin = session('auth.2fa.direct_login', false);
        $redirectUri = session('auth.2fa.redirect_uri');

        if ($clientId) {
            $client = Client::where("client_id", $clientId)->first();
            if ($client) {
                $clientName = $client->name;
            }
        }

        Log::info("showRecoveryForm: Showing recovery form", [
            "user_id" => $userId,
            "client_name" => $clientName
        ]);

        return view('auth.2fa-recovery', [
            'client_name' => $clientName,
            'email' => $user->email,
            'direct_login' => $directLogin,
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'recaptcha_site_key' => config('services.recaptcha.site_key')
        ]);
    }

    /**
     * Verify recovery code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyRecoveryCode(Request $request)
    {
        $request->validate([
            'recovery_code' => 'required|string',
        ]);

        if (!session()->has('auth.2fa.user_id')) {
            Log::warning("verifyRecoveryCode: No user ID in session");
            return redirect()->route('login');
        }

        $userId = session('auth.2fa.user_id');
        $user = User::find($userId);

        if (!$user) {
            Log::warning("verifyRecoveryCode: User not found", [
                "user_id" => $userId
            ]);
            return redirect()->route('login');
        }

        // Verify the recovery code
        $recoveryCodes = $user->two_factor_recovery_codes;

        if (!is_array($recoveryCodes)) {
            Log::warning("verifyRecoveryCode: Invalid recovery codes format", [
                "user_id" => $userId
            ]);
            return redirect()->route('2fa.recovery')
                ->withErrors(['recovery_code' => 'Invalid recovery code format.']);
        }

        if (!in_array($request->recovery_code, $recoveryCodes)) {
            Log::warning("verifyRecoveryCode: Invalid recovery code", [
                "user_id" => $userId,
                "provided_code" => $request->recovery_code
            ]);
            return redirect()->route('2fa.recovery')
                ->withErrors(['recovery_code' => 'Invalid recovery code.']);
        }

        // Remove the used recovery code
        $codes = array_filter($recoveryCodes, function ($code) use ($request) {
            return $code != $request->recovery_code;
        });

        $user->two_factor_recovery_codes = array_values($codes);
        $user->save();

        // Get SSO parameters from session
        $directLogin = session('auth.2fa.direct_login', false);
        $clientId = session('auth.2fa.client_id');
        $redirectUri = session('auth.2fa.redirect_uri');

        Log::info("verifyRecoveryCode: Recovery code used successfully", [
            "user_id" => $userId,
            "remaining_codes" => count($codes),
            "direct_login" => $directLogin,
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri
        ]);

        // Clear 2FA session
        session()->forget([
            'auth.2fa.user_id',
            'auth.2fa.direct_login',
            'auth.2fa.client_id',
            'auth.2fa.redirect_uri'
        ]);

        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();

        // Mark this as a new login to clear any UI preferences
        session(['new_login' => true]);

        // Log the login activity
        LoginActivity::logLogin($user, $request);

        // Set a flash message for next page
        session()->flash('status', 'Recovery code was used successfully. Please regenerate your recovery codes to maintain security.');

        // Handle redirect based on session parameters
        return $this->handleLoginRedirect($user, $directLogin, $clientId, $redirectUri);
    }

    /**
     * Handle the login redirect based on session parameters.
     * This is used by both regular login and after 2FA verification.
     *
     * @param User $user
     * @param bool $directLogin
     * @param string|null $clientId
     * @param string|null $redirectUri
     * @return \Illuminate\Http\RedirectResponse
     */
    private function handleLoginRedirect(User $user, bool $directLogin, ?string $clientId, ?string $redirectUri)
    {
        if ($directLogin) {
            // Direct login to Ordnary account
            Log::info("handleLoginRedirect: Direct login successful, redirecting to profile", [
                "user_id" => $user->id
            ]);
            session()->forget("direct_login");
            return redirect()->route("account.profile");
        } else if ($clientId && $redirectUri) {
            // SSO login with client parameters
            Log::info("handleLoginRedirect: SSO login successful, generating token", [
                "user_id" => $user->id,
                "client_id" => $clientId,
                "redirect_uri" => $redirectUri
            ]);

            $token = $this->generateToken($user, $clientId);
            Log::info("handleLoginRedirect: Generated token for SSO", [
                "user_id" => $user->id,
                "token" => $token,
            ]);

            session()->forget(["client_id", "redirect_uri"]);
            Log::debug("handleLoginRedirect: Cleared session parameters");

            $targetUrl = $redirectUri . "?token=" . $token;
            Log::info("handleLoginRedirect: Redirecting to client URL", ["target_url" => $targetUrl]);
            return redirect()->away($targetUrl);
        } else {
            // Fallback if no valid session parameters (shouldn't happen normally)
            Log::warning("handleLoginRedirect: No valid session parameters for redirect", [
                "user_id" => $user->id,
                "direct_login" => $directLogin,
                "client_id" => $clientId,
                "redirect_uri" => $redirectUri
            ]);
            return redirect()->route("account.profile");
        }
    }

    /**
     * Generate a new token for a user session.
     *
     * @param  \App\Models\User  $user
     * @param  string  $clientId
     * @return string
     */
    private function generateToken(User $user, string $clientId)
    {
        // Ensure any malformed client_id is cleaned up
        if (strpos($clientId, '?') !== false) {
            $parts = explode('?', $clientId);
            $clientId = $parts[0];
        }

        // Verwijder oude tokens van deze gebruiker en client
        UserSession::where('user_id', $user->id)
            ->where('client_id', $clientId)
            ->delete();

        // Beperk het aantal actieve sessies
        $activeSessionCount = UserSession::where('user_id', $user->id)->count();
        if ($activeSessionCount >= 5) {
            UserSession::where('user_id', $user->id)
                ->oldest()
                ->first()
                    ?->delete();
        }

        // Register this service for the user or update last_used timestamp
        UserService::updateOrCreate(
            [
                'user_id' => $user->id,
                'client_id' => $clientId,
            ],
            [
                'last_used' => now(),
                'status' => 'active',
            ]
        );

        Log::info("generateToken: User service registered or updated", [
            "user_id" => $user->id,
            "client_id" => $clientId,
        ]);

        // Gebruik een cryptografisch veilige token generator
        $token = bin2hex(random_bytes(32));

        UserSession::create([
            "user_id" => $user->id,
            "client_id" => $clientId,
            "token" => hash('sha256', $token), // Hash de token voor opslag
            "expires_at" => now()->addHours(24)
        ]);

        return $token;
    }

    /**
     * Show the forgot password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showForgotForm(Request $request)
    {
        return view('auth.password.forgot', [
            "recaptcha_site_key" => config('services.recaptcha.site_key')
        ]);
    }

    /**
     * Handle forgot password form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'g-recaptcha-response' => 'required'
        ]);

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaResult = $this->verifyRecaptcha($recaptchaResponse);

        if (
            !$recaptchaResult['success'] ||
            (isset($recaptchaResult['score']) && $recaptchaResult['score'] < 0.5)
        ) {
            Log::warning("forgotPassword: reCAPTCHA verification failed", [
                "email" => $request->email,
                "recaptcha_errors" => $recaptchaResult['error-codes'] ?? 'Unknown error',
                "score" => $recaptchaResult['score'] ?? 'N/A'
            ]);

            return redirect()->back()->withErrors([
                "recaptcha" => "Security verification failed. Please try again."
            ]);
        }

        // Check if user exists before sending reset link
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Log::info("forgotPassword: Password reset requested for non-existent email", [
                "email" => $request->email
            ]);

            // Don't reveal that the user doesn't exist for security reasons
            return redirect()->route('password.sent')->with('status', 'If your email exists in our system, you will receive a password reset link shortly.');
        }

        // Generate a token
        $token = PasswordBroker::createToken($user);

        // Store token in password_resets table
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Get the reset URL
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $request->email,
        ], false));

        Log::info("forgotPassword: Password reset token created", [
            "email" => $request->email,
            "reset_url" => $resetUrl
        ]);

        // Send the reset link email
        Mail::send('emails.password-reset', ['resetUrl' => $resetUrl], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Password Reset Request for Ordnary');
        });

        return redirect()->route('password.sent')->with('status', 'If your email exists in our system, you will receive a password reset link shortly.');
    }

    /**
     * Show a view that the password reset email has been sent.
     *
     * @return \Illuminate\View\View
     */
    public function showSentForm()
    {
        return view('auth.password.sent');
    }

    /**
     * Show the reset password form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request)
    {
        $token = $request->route('token');
        $email = $request->email;

        // Validate token exists
        $tokenRecord = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if (!$tokenRecord) {
            Log::warning("showResetForm: No token found for email", [
                "email" => $email
            ]);
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Invalid password reset link.']);
        }

        // Check if token is expired (1 hour validity)
        if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
            Log::warning("showResetForm: Token expired", [
                "email" => $email,
                "created_at" => $tokenRecord->created_at
            ]);
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Password reset link has expired. Please request a new one.']);
        }

        return view('auth.password.reset', [
            'token' => $token,
            'email' => $email,
            "recaptcha_site_key" => config('services.recaptcha.site_key')
        ]);
    }

    /**
     * Handle password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'g-recaptcha-response' => 'required'
        ]);

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $recaptchaResult = $this->verifyRecaptcha($recaptchaResponse);

        if (
            !$recaptchaResult['success'] ||
            (isset($recaptchaResult['score']) && $recaptchaResult['score'] < 0.5)
        ) {
            Log::warning("resetPassword: reCAPTCHA verification failed", [
                "email" => $request->email,
                "recaptcha_errors" => $recaptchaResult['error-codes'] ?? 'Unknown error',
                "score" => $recaptchaResult['score'] ?? 'N/A'
            ]);

            return redirect()->back()->withErrors([
                "recaptcha" => "Security verification failed. Please try again."
            ]);
        }

        // Fetch token record
        $tokenRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord) {
            Log::warning("resetPassword: No token found for email", [
                "email" => $request->email
            ]);
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Invalid password reset link.']);
        }

        // Verify token
        if (!Hash::check($request->token, $tokenRecord->token)) {
            Log::warning("resetPassword: Token mismatch", [
                "email" => $request->email
            ]);
            return redirect()->back()
                ->withErrors(['token' => 'Invalid token.']);
        }

        // Check if token is expired (1 hour validity)
        if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
            Log::warning("resetPassword: Token expired", [
                "email" => $request->email,
                "created_at" => $tokenRecord->created_at
            ]);
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'Password reset link has expired. Please request a new one.']);
        }

        // Find the user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Log::warning("resetPassword: User not found", [
                "email" => $request->email
            ]);
            return redirect()->route('password.forgot')
                ->withErrors(['email' => 'We cannot find a user with that email address.']);
        }

        // Reset the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the token
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Log the successful password reset
        Log::info("resetPassword: Password successfully reset", [
            "user_id" => $user->id,
            "email" => $user->email
        ]);

        // Fire the password reset event
        event(new PasswordReset($user));

        return redirect()->route('password.success');
    }

    /**
     * Show a success page after password reset.
     *
     * @return \Illuminate\View\View
     */
    public function showSuccessForm()
    {
        return view('auth.password.success');
    }

    /**
     * Show the registration form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        // Get query parameters the same way as login
        $clientId = $request->query("client_id");
        $redirectUri = $request->query("redirect_uri");
        $fullUrl = $request->fullUrl();

        Log::debug("showRegistrationForm: Received query parameters", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "full_url" => $fullUrl
        ]);

        // Check for malformed URL with second question mark instead of ampersand
        if ($clientId && strpos($clientId, '?redirect_uri=') !== false) {
            // Extract the real client_id and redirect_uri
            $parts = explode('?redirect_uri=', $clientId);
            $clientId = $parts[0];
            $redirectUri = urldecode($parts[1]);

            Log::info("showRegistrationForm: Fixed malformed URL", [
                "corrected_client_id" => $clientId,
                "corrected_redirect_uri" => $redirectUri
            ]);
        }

        // Clean up redirect URI
        if ($redirectUri) {
            $redirectUri = $this->cleanRedirectUri($redirectUri);
        }

        // Check if this is a direct registration (without client parameters)
        if (!$clientId || !$redirectUri) {
            Log::info("showRegistrationForm: Direct registration attempt (no client parameters)", [
                "client_id" => $clientId,
                "redirect_uri" => $redirectUri
            ]);
            // Set a flag in the session to indicate this is a direct registration
            session(["direct_login" => true]);

            return view("auth.register", [
                "client_name" => "Ordnary Account",
                "recaptcha_site_key" => config('services.recaptcha.site_key'),
                "direct_login" => true
            ]);
        }

        // Verify the client exists and the redirect URI is allowed
        $client = Client::where("client_id", $clientId)->first();
        if (!$client) {
            Log::error("showRegistrationForm: Invalid client_id", [
                "client_id" => $clientId,
            ]);
            return view("error", [
                "message" => "Invalid client_id.",
            ]);
        }

        // Check if the redirect URI is in the list of allowed URIs for this client
        $allowedRedirectUris = $this->parseRedirectUris($client->redirect_uris);

        // Normalize the allowed URIs to handle common typos
        $normalizedAllowedUris = $this->normalizeRedirectUris($allowedRedirectUris, "showRegistrationForm");

        if (!in_array($redirectUri, $normalizedAllowedUris)) {
            Log::error("showRegistrationForm: Redirect URI not allowed", [
                "redirect_uri" => $redirectUri,
                "allowed_redirect_uris" => $allowedRedirectUris,
                "normalized_allowed_uris" => $normalizedAllowedUris
            ]);
            return view("error", [
                "message" => "Redirect URI not allowed.",
            ]);
        }

        // Store client_id and redirect_uri in session for use after registration
        session(["client_id" => $clientId, "redirect_uri" => $redirectUri]);
        // Store client name for warning alert
        session(["client_name" => $client->name]);

        return view("auth.register", [
            "client_name" => $client->name,
            "recaptcha_site_key" => config('services.recaptcha.site_key')
        ]);
    }

    /**
     * Handle a registration request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user = User::create([
                'ordnary_id' => 'idnblo_' . Str::uuid(),
                'username' => $request->username,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Log::info("register: User registered successfully", ["email" => $request->email]);

            // Send welcome email
            try {
                // Validate email format before sending
                if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($user->email)->send(new WelcomeEmail($user));
                    Log::info("register: Welcome email sent", ["email" => $user->email]);
                } else {
                    Log::warning("register: Invalid email format, welcome email not sent", ["email" => $user->email]);
                }
            } catch (\Exception $e) {
                Log::error("register: Failed to send welcome email", [
                    "email" => $user->email,
                    "exception" => $e->getMessage(),
                    "trace" => $e->getTraceAsString()
                ]);
                // Continue with registration process even if email fails
            }

            // Fire registered event (this will automatically send verification email)
            event(new Registered($user));

            // Instead of auto-login, redirect to verification notice
            Auth::login($user); // We login the user temporarily so they can see the verification notice
            return redirect()->route('verification.notice');

        } catch (\Exception $e) {
            Log::error("register: Exception occurred during registration", [
                "exception" => $e->getMessage(),
                "stack_trace" => $e->getTraceAsString(),
            ]);
            return redirect()->route("register")->withErrors([
                "error" => "An error occurred while registering. Please try again later.",
            ]);
        }
    }

    /**
     * Parse redirect URIs from database field
     * Handles both JSON arrays and plain strings for backward compatibility
     *
     * @param  string  $redirectUrisField
     * @return array
     */
    private function parseRedirectUris(string $redirectUrisField)
    {
        // Try to decode as JSON first
        $decoded = json_decode($redirectUrisField, true);

        if (is_array($decoded)) {
            // Successfully decoded as JSON array
            return $decoded;
        }

        // If not JSON, treat as a single URI string
        // Handle cases where multiple URIs might be separated by newlines
        $uris = preg_split('/\r\n|\r|\n/', trim($redirectUrisField));
        $uris = array_filter(array_map('trim', $uris)); // Remove empty entries and trim whitespace

        Log::info("parseRedirectUris: Parsed redirect URIs from string format", [
            "original" => $redirectUrisField,
            "parsed" => $uris
        ]);

        return array_values($uris); // Re-index array
    }

    /**
     * Normalize redirect URIs to handle common typos
     *
     * @param array $allowedRedirectUris
     * @param string $context
     * @return array
     */
    private function normalizeRedirectUris(array $allowedRedirectUris, string $context = 'general')
    {
        $normalizedUris = $allowedRedirectUris;

        // Process each allowed URI
        foreach ($allowedRedirectUris as $allowedUri) {
            // Fix the common 'callack' vs 'callback' typo
            if (strpos($allowedUri, '/callack') !== false) {
                $correctedUri = str_replace('/callack', '/callback', $allowedUri);
                $normalizedUris[] = $correctedUri;

                Log::info("{$context}: Added normalized version of allowed URI with 'callack' typo fixed", [
                    "original" => $allowedUri,
                    "corrected" => $correctedUri
                ]);
            }
        }

        return $normalizedUris;
    }

    /**
     * Show the email verification notice.
     *
     * @return \Illuminate\View\View
     */
    public function showVerificationNotice()
    {
        return view('auth.verify-email');
    }

    /**
     * Verify the user's email address.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Your email address is already verified.');
        }

        $user->markEmailAsVerified();

        event(new \Illuminate\Auth\Events\Verified($user));

        Log::info("verifyEmail: User verified email successfully", ["email" => $user->email]);

        return redirect()->route('login')->with('success', 'Your email address has been verified. You can now log in.');
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('login');
        }

        $request->user()->sendEmailVerificationNotification();

        Log::info("resendVerificationEmail: Verification email resent", ["email" => $request->user()->email]);

        return back()->with('resent', true);
    }
}
