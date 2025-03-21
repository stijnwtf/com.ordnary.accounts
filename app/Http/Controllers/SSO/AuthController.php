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
use Google2FA;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        // Log the incoming query parameters
        $clientId = $request->query("client_id");
        $redirectUri = $request->query("redirect_uri");
        Log::debug("showLoginForm: Received query parameters", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
        ]);

        // Check if this is a direct login (without client parameters)
        if (!$clientId || !$redirectUri) {
            Log::info("showLoginForm: Direct login attempt (no client parameters)");
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
        if (!in_array($redirectUri, $allowedRedirectUris)) {
            Log::error("showLoginForm: Redirect URI not allowed", [
                "redirect_uri" => $redirectUri,
                "allowed_redirect_uris" => $allowedRedirectUris,
            ]);
            return view("error", [
                "message" => "Redirect URI not allowed.",
            ]);
        }

        // Store client_id and redirect_uri in session for use after login
        session(["client_id" => $clientId, "redirect_uri" => $redirectUri]);
        // Store client name for warning alert
        session(["client_name" => $client->name]);

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

        // Check if this is a direct login
        $directLogin = session("direct_login");
        $clientId = session("client_id");
        $redirectUri = session("redirect_uri");

        Log::debug("login: Retrieved session parameters", [
            "direct_login" => $directLogin,
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "session_data" => session()->all(),
        ]);

        try {
            if (Auth::attempt($request->only("email", "password"))) {
                Log::info("login: Authentication successful", ["email" => $request->email]);
                $user = Auth::user();

                // Check if 2FA is enabled
                if ($user->hasTwoFactorEnabled()) {
                    Log::info("login: 2FA enabled for user", ["user_id" => $user->id]);
                    
                    // Store user ID in session for 2FA verification
                    session(['2fa_user_id' => $user->id]);
                    
                    // Store client information if present
                    if (!$directLogin) {
                        session([
                            'client_id' => $clientId,
                            'redirect_uri' => $redirectUri,
                            'client_name' => session('client_name')
                        ]);
                    }
                    
                    // Log the user out temporarily
                    Auth::logout();
                    
                    // Redirect to 2FA verification page
                    return redirect()->route('2fa');
                }

                // If no 2FA, proceed with normal login
                $request->session()->regenerate();

                // Log the login activity
                LoginActivity::logLogin($user, $request);

                // If this is a direct login, redirect to the profile page
                if ($directLogin) {
                    Log::info("login: Direct login successful, redirecting to profile");
                    session()->forget(["direct_login"]);
                    return redirect()->route("account.profile");
                }

                if (!$clientId || !$redirectUri) {
                    Log::error("login: Missing session parameters after authentication", [
                        "client_id" => $clientId,
                        "redirect_uri" => $redirectUri,
                    ]);
                    return redirect()->route("login")->withErrors([
                        "error" => "Session expired. Please try logging in again.",
                    ]);
                }

                $token = $this->generateToken($user, $clientId);
                Log::info("login: Generated token for user", [
                    "user_id" => $user->id,
                    "token" => $token,
                ]);

                session()->forget(["client_id", "redirect_uri"]);
                Log::debug("login: Cleared session parameters");

                $targetUrl = $redirectUri . "?token=" . $token;
                Log::info("login: Redirecting to client URL", ["target_url" => $targetUrl]);
                return redirect()->away($targetUrl);
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
        Log::info("logout: User logging out", ["user_id" => Auth::id()]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/");
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
        Log::debug("showRegistrationForm: Received query parameters", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
        ]);

        // Check if this is a direct registration (without client parameters)
        if (!$clientId || !$redirectUri) {
            Log::info("showRegistrationForm: Direct registration attempt (no client parameters)");
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
        if (!in_array($redirectUri, $allowedRedirectUris)) {
            Log::error("showRegistrationForm: Redirect URI not allowed", [
                "redirect_uri" => $redirectUri,
                "allowed_redirect_uris" => $allowedRedirectUris,
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

        // Check if this is a direct registration
        $directLogin = session("direct_login");
        $clientId = session("client_id");
        $redirectUri = session("redirect_uri");

        Log::debug("register: Retrieved session parameters", [
            "direct_login" => $directLogin,
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
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

    public function directLogin(Request $request)
    {
        // ... existing code ...
        if ($user) {
            // Log the login activity
            LoginActivity::logLogin($user, $request);
            
            // ... rest of the existing code ...
        }
        // ... existing code ...
    }

    public function directRegister(Request $request)
    {
        // ... existing code ...
        if ($user) {
            // Log the login activity for new registrations
            LoginActivity::logLogin($user, $request);
            
            // ... rest of the existing code ...
        }
        // ... existing code ...
    }

    /**
     * Show the 2FA verification form.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show2FAForm()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('2fa_user_id'));
        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('login');
        }

        return view('auth.2fa', [
            'client_name' => session('client_name', 'Numblio'),
            'direct_login' => session('direct_login', false),
            'client_id' => session('client_id'),
            'redirect_uri' => session('redirect_uri')
        ]);
    }

    /**
     * Handle 2FA verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('2fa_user_id'));
        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('login');
        }

        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            // Clear 2FA session data
            session()->forget(['2fa_user_id', 'client_id', 'redirect_uri', 'client_name', 'direct_login']);
            
            // Log the user in and regenerate session
            Auth::login($user);
            $request->session()->regenerate();

            // Log the login activity
            LoginActivity::logLogin($user, $request);

            // Handle client redirect if present
            if (session()->has('client_id') && session()->has('redirect_uri')) {
                $client = Client::find(session('client_id'));
                if ($client && $client->redirect_uri === session('redirect_uri')) {
                    $token = $this->generateToken($user, session('client_id'));
                    return redirect()->away(session('redirect_uri') . '?token=' . $token);
                }
            }

            // For direct login, redirect to the profile page
            if (session()->has('direct_login')) {
                return redirect()->intended(route('account.profile'));
            }

            // Default redirect to account page
            return redirect()->intended(route('account.profile'));
        }

        return back()->withErrors([
            'code' => 'Invalid verification code.',
        ]);
    }

    /**
     * Show the recovery code form.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showRecoveryForm()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('2fa_user_id'));
        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('login');
        }

        return view('auth.2fa-recovery', [
            'client_name' => session('client_name', 'Numblio'),
            'direct_login' => session('direct_login', false),
            'client_id' => session('client_id'),
            'redirect_uri' => session('redirect_uri')
        ]);
    }

    /**
     * Handle recovery code verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyRecoveryCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find(session('2fa_user_id'));
        if (!$user || !$user->hasTwoFactorEnabled()) {
            return redirect()->route('login');
        }

        $codes = $user->two_factor_recovery_codes;
        $code = $request->code;

        if (in_array($code, $codes)) {
            // Remove used recovery code
            $codes = array_diff($codes, [$code]);
            $user->two_factor_recovery_codes = array_values($codes);
            $user->save();

            // Clear 2FA session data
            session()->forget(['2fa_user_id', 'client_id', 'redirect_uri', 'client_name', 'direct_login']);
            
            // Log the user in
            Auth::login($user);
            $request->session()->regenerate();

            // Log the login activity
            LoginActivity::logLogin($user, $request);

            // Handle client redirect if present
            if (session()->has('client_id') && session()->has('redirect_uri')) {
                $client = Client::find(session('client_id'));
                if ($client && $client->redirect_uri === session('redirect_uri')) {
                    $token = $this->generateToken($user, session('client_id'));
                    return redirect()->away(session('redirect_uri') . '?token=' . $token);
                }
            }

            // For direct login, redirect to the profile page
            if (session()->has('direct_login')) {
                return redirect()->intended(route('account.profile'));
            }

            // Default redirect to account page
            return redirect()->intended(route('account.profile'));
        }

        return back()->withErrors([
            'code' => 'Invalid recovery code.',
        ]);
    }
}
