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
                // Direct login to Numblio account
                return redirect()->route("account.profile");
            } 
            else if ($clientId && $redirectUri) {
                // SSO login with client parameters
                $client = Client::where("client_id", $clientId)->first();
                
                if (!$client) {
                    Log::error("showLoginForm: Invalid client_id for logged in user", [
                        "client_id" => $clientId,
                    ]);
                    return redirect()->route("account.profile");
                }
                
                // Verify the redirect URI is allowed
                $allowedRedirectUris = json_decode($client->redirect_uris) ?: [];
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
                "client_name" => "Numblio Account",
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
        $allowedRedirectUris = json_decode($client->redirect_uris) ?: [];

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
        $directLogin = session("direct_login", false);
        $clientId = session("client_id", $request->input('client_id'));
        $redirectUri = session("redirect_uri", $request->input('redirect_uri'));

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
                Log::info("login: Authentication successful", ["email" => $request->email]);
                $request->session()->regenerate();

                // Log the login activity
                LoginActivity::logLogin(Auth::user(), $request);

                // Handle redirect based on session parameters
                if ($directLogin) {
                    // Direct login to Numblio account
                    Log::info("login: Direct login successful, redirecting to profile");
                    session()->forget("direct_login");
                    return redirect()->route("account.profile");
                }
                else if ($clientId && $redirectUri) {
                    // SSO login with client parameters
                    Log::info("login: SSO login successful, generating token", [
                        "client_id" => $clientId,
                        "redirect_uri" => $redirectUri
                    ]);

                    $token = $this->generateToken(Auth::user(), $clientId);
                    Log::info("login: Generated token for SSO", [
                        "user_id" => Auth::user()->id,
                        "token" => $token,
                    ]);

                    session()->forget(["client_id", "redirect_uri"]);
                    Log::debug("login: Cleared session parameters");

                    $targetUrl = $redirectUri . "?token=" . $token;
                    Log::info("login: Redirecting to client URL", ["target_url" => $targetUrl]);
                    return redirect()->away($targetUrl);
                }
                else {
                    // Fallback if no valid session parameters (shouldn't happen normally)
                    Log::warning("login: No valid session parameters for redirect", [
                        "direct_login" => $directLogin,
                        "client_id" => $clientId,
                        "redirect_uri" => $redirectUri
                    ]);
                    return redirect()->route("account.profile");
                }
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
        
        // Get the active services for this user
        $activeServices = [];
        if ($userId) {
            $activeServices = UserService::where('user_id', $userId)
                ->where('status', 'active')
                ->with('client:client_id,name,logout_url')
                ->get();
                
            Log::info("logout: Found active services for user", [
                "user_id" => $userId,
                "service_count" => $activeServices->count()
            ]);
        }
        
        // Log the user out from the current application
        Log::info("logout: User logging out", ["user_id" => $userId]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Process Single Logout for connected services
        $logoutRedirects = [];
        
        foreach ($activeServices as $service) {
            // Only include services with a logout URL
            if ($service->client && !empty($service->client->logout_url)) {
                $logoutRedirects[] = $service->client->logout_url;
                
                // Back-channel logout notification (server-to-server)
                try {
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

        if (!$recaptchaResult['success'] ||
            (isset($recaptchaResult['score']) && $recaptchaResult['score'] < 0.5)) {
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
        Mail::send('emails.password-reset', ['resetUrl' => $resetUrl], function($message) use ($request) {
            $message->to($request->email);
            $message->subject('Password Reset Request for Numblio');
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

        if (!$recaptchaResult['success'] ||
            (isset($recaptchaResult['score']) && $recaptchaResult['score'] < 0.5)) {
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
                "client_name" => "Numblio Account",
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
        $allowedRedirectUris = json_decode($client->redirect_uris) ?: [];

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
            "name" => ["required", "string", "max:255"],
            "email" => ["required", "string", "email", "max:255", "unique:users"],
            "password" => ["required", "confirmed", Password::defaults()],
        ]);

        // Check for recaptcha response manually
        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (empty($recaptchaResponse)) {
            Log::warning("register: Missing reCAPTCHA response", [
                "email" => $request->email
            ]);

            // Check if this is a direct registration
            if (session("direct_login")) {
                return redirect()->route("register")->withErrors([
                    "recaptcha" => "Security verification failed. Please try again."
                ]);
            }

            $clientId = session("client_id");
            $redirectUri = session("redirect_uri");
            $redirectUrl = route("register", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            return redirect($redirectUrl)->withErrors([
                "recaptcha" => "Security verification failed. Please try again."
            ]);
        }

        // Verify reCAPTCHA
        $recaptchaResult = $this->verifyRecaptcha($recaptchaResponse);

        if (!$recaptchaResult['success']) {
            Log::warning("register: reCAPTCHA verification failed", [
                "email" => $request->email,
                "recaptcha_errors" => $recaptchaResult['error-codes'] ?? 'Unknown error',
                "score" => $recaptchaResult['score'] ?? 'N/A'
            ]);

            // Check if this is a direct registration
            if (session("direct_login")) {
                return redirect()->route("register")->withErrors([
                    "recaptcha" => "Security verification failed. Please try again."
                ]);
            }

            $clientId = session("client_id");
            $redirectUri = session("redirect_uri");
            $redirectUrl = route("register", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            return redirect($redirectUrl)->withErrors([
                "recaptcha" => "Security verification failed. Please try again."
            ]);
        }

        // Check score for invisible reCAPTCHA v3 (if score is too low, reject the registration)
        if (isset($recaptchaResult['score']) && $recaptchaResult['score'] < 0.5) {
            Log::warning("register: reCAPTCHA score too low", [
                "email" => $request->email,
                "score" => $recaptchaResult['score']
            ]);

            // Check if this is a direct registration
            if (session("direct_login")) {
                return redirect()->route("register")->withErrors([
                    "recaptcha" => "Security verification failed. Please try again later."
                ]);
            }

            $clientId = session("client_id");
            $redirectUri = session("redirect_uri");
            $redirectUrl = route("register", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            return redirect($redirectUrl)->withErrors([
                "recaptcha" => "Security verification failed. Please try again later."
            ]);
        }

        // Get session variables or input values as fallback
        $directLogin = session("direct_login", false);
        $clientId = session("client_id", $request->input('client_id'));
        $redirectUri = session("redirect_uri", $request->input('redirect_uri'));

        // If values came from input and not session, store them in session for later
        if (!session("client_id") && $request->input('client_id')) {
            session(["client_id" => $clientId]);
        }
        if (!session("redirect_uri") && $request->input('redirect_uri')) {
            session(["redirect_uri" => $redirectUri]);
        }

        Log::debug("register: Retrieved parameters", [
            "direct_login" => $directLogin,
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "session_data" => session()->all(),
            "request_data" => $request->only(['client_id', 'redirect_uri'])
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
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

            // Fire registered event
            event(new Registered($user));

            // Auto-login after registration
            Auth::login($user);
            $request->session()->regenerate();

            // Log the login activity for new registration
            LoginActivity::logLogin($user, $request);

            // If this is a direct registration, redirect to the profile page
            if ($directLogin) {
                Log::info("register: Direct registration successful, redirecting to profile");
                session()->forget(["direct_login"]);
                return redirect()->route("account.profile");
            }

            if (!$clientId || !$redirectUri) {
                Log::error("register: Missing session parameters after authentication", [
                    "client_id" => $clientId,
                    "redirect_uri" => $redirectUri,
                ]);
                return redirect()->route("login")->withErrors([
                    "error" => "Session expired. Please try logging in again.",
                ]);
            }

            $token = $this->generateToken($user, $clientId);
            Log::info("register: Generated token for user", [
                "user_id" => $user->id,
                "token" => $token,
            ]);

            session()->forget(["client_id", "redirect_uri"]);
            Log::debug("register: Cleared session parameters");

            $targetUrl = $redirectUri . "?token=" . $token;
            Log::info("register: Redirecting to client URL", ["target_url" => $targetUrl]);
            return redirect()->away($targetUrl);

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
}
