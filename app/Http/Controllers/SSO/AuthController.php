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
                    "Ontbrekende parameters: client_id en redirect_uri zijn vereist.",
            ]);
        }

        // Verify the client exists and the redirect URI is allowed
        $client = Client::where("client_id", $clientId)->first();
        if (!$client) {
            Log::error("showLoginForm: Invalid client_id", [
                "client_id" => $clientId,
            ]);
            return view("error", [
                "message" => "Ongeldige client_id.",
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
                "message" => "Ongeldige redirect_uri voor deze client.",
            ]);
        }

        // Store client_id and redirect_uri in session for use after login
        session(["client_id" => $clientId, "redirect_uri" => $redirectUri]);
        Log::debug("showLoginForm: Stored client_id and redirect_uri in session", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "session_data" => session()->all(), // Extra: log alle sessiedata
        ]);

        Log::debug("showLoginForm: Stored client_id and redirect_uri in session", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "session_data" => session()->all(),
        ]);

        Log::debug("showLoginForm: Sessie vóór view render", [
            "session_data" => session()->all(),
            "errors" => session('errors') ? session('errors')->all() : 'Geen errors'
        ]);

        return view("auth.login");
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
        ]);

        // Get client_id and redirect_uri from the session
        $clientId = session("client_id");
        $redirectUri = session("redirect_uri");
        Log::debug("login: Retrieved session parameters", [
            "client_id" => $clientId,
            "redirect_uri" => $redirectUri,
            "session_data" => session()->all(), // Extra: log alle sessiedata
        ]);

        try {
            // Test database connectiviteit
            Log::debug("login: Database connectiviteit testen", ["email" => $request->email]);
            DB::connection()->getPdo();
            Log::debug("login: Database connectie OK");

            Log::debug("login: Voor Auth::attempt", ["email" => $request->email]);
            if (Auth::attempt($request->only("email", "password"))) {
                Log::info("login: Authentication successful", [
                    "email" => $request->email,
                ]);
                $request->session()->regenerate();

                if (!$clientId || !$redirectUri) {
                    Log::error(
                        "login: Missing session parameters after authentication",
                        [
                            "client_id" => $clientId,
                            "redirect_uri" => $redirectUri,
                        ]
                    );
                    return redirect()
                        ->route("login")
                        ->withErrors([
                            "error" =>
                                "Sessie verlopen. Probeer opnieuw in te loggen.",
                        ]);
                }

                // Generate a token for this session
                $token = $this->generateToken(Auth::user(), $clientId);
                Log::info("login: Generated token for user", [
                    "user_id" => Auth::user()->id,
                    "token" => $token,
                ]);

                // Clear session data
                session()->forget(["client_id", "redirect_uri"]);
                Log::debug("login: Cleared session parameters");

                // Redirect to the client with the token
                $targetUrl = $redirectUri . "?token=" . $token;
                Log::info("login: Redirecting to client URL", [
                    "target_url" => $targetUrl,
                ]);
                return redirect()->away($targetUrl);
            }

            // Als de login mislukt: controleer of het e-mailadres bestaat
            Log::debug("login: Na mislukte Auth::attempt", ["email" => $request->email]);
            Log::debug("login: Voor email check", ["email" => $request->email]);
            $emailExists = User::where("email", $request->email)->exists();
            Log::debug("login: Na email check", ["email_exists" => $emailExists]);

            // Extra check: als sessiedata ontbreekt, fallback
            if (!$clientId || !$redirectUri) {
                Log::error("login: Sessiedata ontbreekt voor redirect", [
                    "client_id" => $clientId,
                    "redirect_uri" => $redirectUri,
                ]);
                return redirect()->route("login")->withErrors([
                    "error" => "Sessiedata ontbreekt. Probeer opnieuw.",
                ]);
            }

            // Bouw de redirect URL met de vereiste queryparameters
            $redirectUrl =
                route("login") .
                "?client_id=" .
                urlencode($clientId) .
                "&redirect_uri=" .
                urlencode($redirectUri);
            Log::debug("login: Redirect URL built", [
                "redirect_url" => $redirectUrl,
            ]);

            Log::debug("login: Sessie check voor redirect", [
                "client_id" => $clientId,
                "redirect_uri" => $redirectUri,
                "redirect_url" => $redirectUrl,
            ]);

            if (!$emailExists) {
                Log::warning("login: Email not found", [
                    "email" => $request->email,
                ]);
                return redirect($redirectUrl)->withErrors([
                    "error" =>
                        "E-mailadres niet gevonden. Controleer je e-mailadres.",
                ]);
            } else {
                Log::warning("login: Incorrect password for email", [
                    "email" => $request->email,
                ]);
                return redirect($redirectUrl)->withErrors([
                    "error" => "Wachtwoord is incorrect. Probeer het opnieuw.",
                ]);
            }
        } catch (\Exception $e) {
            Log::error("login: Exception occurred during login", [
                "exception" => $e->getMessage(),
                "stack_trace" => $e->getTraceAsString(),
            ]);
            return redirect()
                ->route("login")
                ->withErrors([
                    "error" =>
                        "Er is een fout opgetreden tijdens het inloggen. Probeer het later opnieuw.",
                ]);
        }
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
                    "message" => "Ongeldige client credentials.",
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
                    "message" => "Token is verlopen.",
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
                    "message" => "Ongeldig of verlopen token.",
                ],
                401
            );
        }

        // Log token expiry check
        Log::info("validateToken: Token expiry check", [
            "expires_at" => $userSession->expires_at,
            "current_time" => now(),
        ]);

        // Get the user data
        $user = User::find($userSession->user_id);
        if (!$user) {
            Log::error("validateToken: User not found for token", [
                "token" => $request->token,
            ]);
            return response()->json(
                [
                    "success" => false,
                    "message" => "Gebruiker niet gevonden.",
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
}
