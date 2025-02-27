<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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

        if (!$clientId || !$redirectUri) {
            Log::error("showLoginForm: Missing required parameters", [
                "client_id" => $clientId,
                "redirect_uri" => $redirectUri,
            ]);
            return view("error", [
                "message" =>
                    "Missing parameters: client_id and redirect_uri are required.",
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
            "g-recaptcha-response" => "required" // Make sure this is present
        ]);

        // Verify reCAPTCHA
        $recaptchaResponse = $this->verifyRecaptcha($request->input('g-recaptcha-response'));

        if (!$recaptchaResponse['success']) {
            Log::warning("login: reCAPTCHA verification failed", [
                "email" => $request->email,
                "recaptcha_errors" => $recaptchaResponse['error-codes'] ?? 'Unknown error',
                "score" => $recaptchaResponse['score'] ?? 'N/A'
            ]);

            $clientId = session("client_id");
            $redirectUri = session("redirect_uri");
            $redirectUrl = route("login", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            return redirect($redirectUrl)->withErrors([
                "recaptcha" => "Security verification failed. Please try again."
            ]);
        }

        // Check score for invisible reCAPTCHA v3 (if score is too low, reject the login)
        if (isset($recaptchaResponse['score']) && $recaptchaResponse['score'] < 0.5) {
            Log::warning("login: reCAPTCHA score too low", [
                "email" => $request->email,
                "score" => $recaptchaResponse['score']
            ]);

            $clientId = session("client_id");
            $redirectUri = session("redirect_uri");
            $redirectUrl = route("login", ["client_id" => $clientId, "redirect_uri" => $redirectUri]);

            return redirect($redirectUrl)->withErrors([
                "recaptcha" => "Security verification failed. Please try again later."
            ]);
        }

        $clientId = session("client_id");
        $redirectUri = session("redirect_uri");
        Log::debug("login: Retrieved session parameters", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "session_data" => session()->all(),
        ]);

        try {
            if (Auth::attempt($request->only("email", "password"))) {
                Log::info("login: Authentication successful", ["email" => $request->email]);
                $request->session()->regenerate();

                if (!$clientId || !$redirectUri) {
                    Log::error("login: Missing session parameters after authentication", [
                        "client_id" => $clientId,
                        "redirect_uri" => $redirectUri,
                    ]);
                    return redirect()->route("login")->withErrors([
                        "error" => "Session expired. Please try logging in again.",
                    ]);
                }

                $token = $this->generateToken(Auth::user(), $clientId);
                Log::info("login: Generated token for user", [
                    "user_id" => Auth::user()->id,
                    "token" => $token,
                ]);

                session()->forget(["client_id", "redirect_uri"]);
                Log::debug("login: Cleared session parameters");

                $targetUrl = $redirectUri . "?token=" . $token;
                Log::info("login: Redirecting to client URL", ["target_url" => $targetUrl]);
                return redirect()->away($targetUrl);
            }

            $emailExists = User::where("email", $request->email)->exists();

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

        // Find the user session with this token
        $userSession = UserSession::where("token", $request->token)
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
        // Generate a unique token
        $token = Str::random(64);
        Log::debug("generateToken: Generated token", ["token" => $token]);

        // Save the token to the database
        UserSession::create([
            "user_id" => $user->id,
            "client_id" => $clientId,
            "token" => $token,
            "expires_at" => now()->addHours(24), // Token expires after 24 hours
        ]);

        Log::info("generateToken: Token saved to database", [
            "user_id" => $user->id,
            "client_id" => $clientId,
            "expires_at" => now()->addHours(24),
        ]);

        return $token;
    }

    public function showForgotForm(Request $request)
    {
        return view('auth.password.forgot', [
            "recaptcha_site_key" => config('services.recaptcha.site_key')
        ]);
    }
}
