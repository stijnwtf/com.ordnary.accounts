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
use Illuminate\Validation\ValidationException;

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
        // Validate client_id and redirect_uri parameters
        $clientId = $request->query('client_id');
        $redirectUri = $request->query('redirect_uri');

        if (!$clientId || !$redirectUri) {
            return view('error', [
                'message' => 'Ontbrekende parameters: client_id en redirect_uri zijn vereist.'
            ]);
        }

        // Verify the client exists and the redirect URI is allowed
        $client = Client::where('client_id', $clientId)->first();
        if (!$client) {
            return view('error', [
                'message' => 'Ongeldige client_id.'
            ]);
        }

        // Check if the redirect URI is in the list of allowed URIs for this client
        $allowedRedirectUris = json_decode($client->redirect_uris) ?: [];
        if (!in_array($redirectUri, $allowedRedirectUris)) {
            return view('error', [
                'message' => 'Ongeldige redirect_uri voor deze client.'
            ]);
        }

        // Store client_id and redirect_uri in session for use after login
        session(['client_id' => $clientId, 'redirect_uri' => $redirectUri]);

        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Get client_id and redirect_uri from session
            $clientId = session('client_id');
            $redirectUri = session('redirect_uri');

            if (!$clientId || !$redirectUri) {
                return redirect()->route('login')
                    ->with('error', 'Sessie verlopen. Probeer opnieuw in te loggen.');
            }

            // Generate a token for this session
            $token = $this->generateToken(Auth::user(), $clientId);

            // Clear the session data
            session()->forget(['client_id', 'redirect_uri']);

            // Redirect back to the client application with the token
            return redirect()->away($redirectUri . '?token=' . $token);
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
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
        'token' => 'required|string',
        'client_id' => 'required|string',
        'client_secret' => 'required|string',
    ]);

    Log::info('Token validation started', [
        'token' => $request->token,
        'client_id' => $request->client_id,
    ]);

    // Verify client credentials
    $client = Client::where('client_id', $request->client_id)
        ->where('client_secret', $request->client_secret)
        ->first();

    if (!$client) {
        Log::error('Invalid client credentials', ['client_id' => $request->client_id]);
        return response()->json([
            'success' => false,
            'message' => 'Ongeldige client credentials.',
        ], 401);
    }

    // Find the user session with this token
    $userSession = UserSession::where('token', $request->token)
        ->where('client_id', $request->client_id)
        ->first();

    if (!$userSession) {
        Log::warning('Invalid or expired token', [
            'token' => $request->token,
            'client_id' => $request->client_id,
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Ongeldig of verlopen token.',
        ], 401);
    }

    // Log token expiration check
    Log::info('Token expiry check', [
        'expires_at' => $userSession->expires_at,
        'current_time' => now(),
    ]);

    // Check if token has expired
    if ($userSession->expires_at <= now()) {
        Log::warning('Token has expired', [
            'token' => $request->token,
            'expires_at' => $userSession->expires_at,
            'current_time' => now(),
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Token is verlopen.',
        ], 419);
    }

    // Get the user data
    $user = User::find($userSession->user_id);
    if (!$user) {
        Log::error('User not found for token', ['token' => $request->token]);
        return response()->json([
            'success' => false,
            'message' => 'Gebruiker niet gevonden.',
        ], 404);
    }

    // Return user data
    return response()->json([
        'success' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            // Add any other user data you want to share with the client application
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

        // Save the token to the database
        UserSession::create([
            'user_id' => $user->id,
            'client_id' => $clientId,
            'token' => $token,
            'expires_at' => now()->addHours(24), // Token expires after 24 hours
        ]);

        return $token;
    }
}
