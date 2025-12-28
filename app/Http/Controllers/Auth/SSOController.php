<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SSOController extends Controller
{
    protected $ssoServerUrl;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->ssoServerUrl = config('sso.server_url');
        $this->clientId = config('sso.client_id');
        $this->clientSecret = config('sso.client_secret');
    }

    public function redirectToSso(Request $request)
    {
        // Controleer of we in lokale ontwikkelomgeving zijn
        if (app()->environment('local') && config('app.debug')) {
            // Stuur door naar lokale login pagina voor ontwikkeling
            return redirect()->route('local.login.form');
        }

        if (!$request->session()->has('url.intended')) {
            $request->session()->put('url.intended', url()->previous());
        }

        $redirectUri = url('/sso/callback');
        $url = $this->ssoServerUrl . '/login?client_id=' . $this->clientId . '&redirect_uri=' . urlencode($redirectUri);

        Log::info('Redirecting to SSO server', ['url' => $url]);

        return redirect()->away($url);
    }

    public function showLocalLoginForm()
    {
        // Alleen toegankelijk in lokale ontwikkelomgeving
        if (!app()->environment('local') || !config('app.debug')) {
            Log::warning('DEV:Attempt to access local login in non-local environment');
            return redirect()->route('loginPage');
        }

        return view('auth.local-login');
    }

    public function localLogin(Request $request)
    {
        // Alleen toegankelijk in lokale ontwikkelomgeving
        if (!app()->environment('local') || !config('app.debug')) {
            Log::warning('DEV:Attempt to use local login in non-local environment');
            return redirect()->route('loginPage');
        }

        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
        ]);

        // Simuleer een gebruiker voor lokale ontwikkeling
        $user = User::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make('local_dev_password'), // Veilige placeholder password
            ]
        );

        Auth::login($user);

        // Reset failed login attempts on successful login
        $user->resetFailedLoginAttempts();

        Log::info('Local development login successful', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->intended('/dashboard');
    }

    public function handleCallback(Request $request)
    {
        $token = $request->query('token');
        $refreshToken = $request->query('refresh_token');

        if (!$token) {
            Log::warning('No token received from SSO server');
            return redirect()->route('loginPage')->with('error', 'Authenticatie mislukt. Geen token ontvangen van de SSO server.');
        }

        Log::info('Token received, validating with SSO server');

        try {
            // Simuleer succesvolle validatie indien in lokale ontwikkelomgeving
            if (app()->environment('local') && config('app.debug')) {
                // Simuleer gebruikersdata voor lokale ontwikkeling
                $userData = [
                    'email' => 'dev@example.com',
                    'name' => 'Local Developer',
                ];

                $user = User::firstOrCreate(
                    ['email' => $userData['email']],
                    [
                        'name' => $userData['name'],
                        'password' => '',
                    ]
                );

                Auth::login($user);

                // Reset failed login attempts on successful login
                $user->resetFailedLoginAttempts();

                Log::info('Local development SSO simulation', ['user_id' => $user->id]);

                return redirect()->intended('/dashboard');
            }

            // Normale SSO validatie voor niet-lokale omgevingen
            $response = Http::post($this->ssoServerUrl . '/api/v1/validate-token', [
                'token' => $token,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            $data = $response->json();

            if (!$response->successful()) {
                // Check if token is expired
                if (isset($data['code']) && $data['code'] === 'token_expired' && $refreshToken) {
                    Log::info('Token expired, attempting to refresh');
                    return $this->refreshAndLogin($token, $refreshToken);
                }

                $errorMessage = $data['message'] ?? 'Onbekende fout';
                Log::error('Token validation failed', [
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                    'message' => $data['message'] ?? 'Onbekende fout',
                ]);
                return redirect()->route('loginPage')->with('error', 'Token validatie mislukt: ' . $errorMessage);
            }

            if (!isset($data['success']) || !$data['success']) {
                $errorMessage = $data['message'] ?? 'Onbekende fout';
                Log::error('Token validation failed', [
                    'message' => $errorMessage,
                ]);
                return redirect()->route('loginPage')->with('error', 'Token validatie mislukt: ' . $errorMessage);
            }

            Log::info('Token validated successfully', ['user' => $data['user']]);

            $userData = $data['user'];

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => '',
                ]
            );

            // Store tokens in session for later use
            if ($refreshToken) {
                Session::put('sso_refresh_token', $refreshToken);
            }
            Session::put('sso_token', $token);
            Session::put('sso_token_expiry', $data['exp'] ?? null);

            Auth::login($user);

            // Reset failed login attempts on successful login
            $user->resetFailedLoginAttempts();

            Log::info('User logged in successfully', ['user_id' => $user->id]);

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            Log::error('Error during SSO login processing', ['exception' => $e->getMessage()]);
            return redirect()->route('loginPage')->with('error', 'Er is een fout opgetreden tijdens het verwerken van de SSO aanmelding: ' . $e->getMessage());
        }
    }

    /**
     * Refresh the token and log in the user
     */
    protected function refreshAndLogin($expiredToken, $refreshToken)
    {
        try {
            $response = Http::post($this->ssoServerUrl . '/api/v1/refresh-token', [
                'token' => $expiredToken,
                'refresh_token' => $refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            $data = $response->json();

            if (!$response->successful() || !isset($data['success']) || !$data['success']) {
                $errorMessage = $data['message'] ?? 'Onbekende fout';
                Log::error('Token refresh failed', [
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
                return redirect()->route('loginPage')->with('error', 'Token vernieuwing mislukt: ' . $errorMessage);
            }

            // Get the new tokens
            $newToken = $data['token'];
            $newRefreshToken = $data['refresh_token'];

            // Validate the new token
            return $this->handleTokenValidation($newToken, $newRefreshToken);
        } catch (\Exception $e) {
            Log::error('Error during token refresh', ['exception' => $e->getMessage()]);
            return redirect()->route('loginPage')->with('error', 'Er is een fout opgetreden tijdens het vernieuwen van het token: ' . $e->getMessage());
        }
    }

    /**
     * Validate a token and log in the user
     */
    protected function handleTokenValidation($token, $refreshToken = null)
    {
        try {
            $response = Http::post($this->ssoServerUrl . '/api/v1/validate-token', [
                'token' => $token,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            $data = $response->json();

            if (!$response->successful() || !isset($data['success']) || !$data['success']) {
                $errorMessage = $data['message'] ?? 'Onbekende fout';
                Log::error('New token validation failed', [
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                ]);
                return redirect()->route('loginPage')->with('error', 'Nieuwe token validatie mislukt: ' . $errorMessage);
            }

            Log::info('New token validated successfully', ['user' => $data['user']]);

            $userData = $data['user'];

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => '',
                ]
            );

            // Store tokens in session for later use
            Session::put('sso_token', $token);
            if ($refreshToken) {
                Session::put('sso_refresh_token', $refreshToken);
            }
            Session::put('sso_token_expiry', $data['exp'] ?? null);

            Auth::login($user);

            // Reset failed login attempts on successful login
            $user->resetFailedLoginAttempts();

            Log::info('User logged in successfully with new token', ['user_id' => $user->id]);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('Error during new token validation', ['exception' => $e->getMessage()]);
            return redirect()->route('loginPage')->with('error', 'Er is een fout opgetreden tijdens het valideren van het nieuwe token: ' . $e->getMessage());
        }
    }

    /**
     * Check if the current token is valid or needs refreshing
     * This can be used in middleware to ensure the user's session is valid
     */
    public function checkAndRefreshToken()
    {
        // Skip in local environment
        if (app()->environment('local') && config('app.debug')) {
            return true;
        }

        if (!Auth::check()) {
            return false;
        }

        $token = Session::get('sso_token');
        $refreshToken = Session::get('sso_refresh_token');
        $expiry = Session::get('sso_token_expiry');

        // If no token or no expiry, we can't validate
        if (!$token || !$expiry) {
            return false;
        }

        // If token is not expired yet, it's valid
        if ($expiry > time()) {
            return true;
        }

        // Token is expired, try to refresh if we have a refresh token
        if ($refreshToken) {
            try {
                $response = Http::post($this->ssoServerUrl . '/api/v1/refresh-token', [
                    'token' => $token,
                    'refresh_token' => $refreshToken,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);

                $data = $response->json();

                if ($response->successful() && isset($data['success']) && $data['success']) {
                    // Update tokens in session
                    Session::put('sso_token', $data['token']);
                    Session::put('sso_refresh_token', $data['refresh_token']);

                    // Validate the new token to get expiry
                    $validationResponse = Http::post($this->ssoServerUrl . '/api/v1/validate-token', [
                        'token' => $data['token'],
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                    ]);

                    $validationData = $validationResponse->json();
                    if ($validationResponse->successful() && isset($validationData['exp'])) {
                        Session::put('sso_token_expiry', $validationData['exp']);
                        return true;
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error refreshing token in middleware', ['exception' => $e->getMessage()]);
            }
        }

        // If we get here, token refresh failed or we don't have a refresh token
        Auth::logout();
        Session::forget(['sso_token', 'sso_refresh_token', 'sso_token_expiry']);
        return false;
    }

    public function logout(Request $request)
    {
        // Revoke the token on the SSO server if we have one
        $token = Session::get('sso_token');
        if ($token && (!app()->environment('local') || !config('app.debug'))) {
            try {
                Http::post($this->ssoServerUrl . '/api/v1/revoke-token', [
                    'token' => $token,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);
            } catch (\Exception $e) {
                Log::error('Error revoking token', ['exception' => $e->getMessage()]);
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Clear SSO tokens
        Session::forget(['sso_token', 'sso_refresh_token', 'sso_token_expiry']);

        Log::info('User logged out');

        // Alleen doorsturen naar SSO logout als we niet in lokale omgeving zijn
        if (!app()->environment('local') || !config('app.debug')) {
            return redirect()->away($this->ssoServerUrl . '/logout');
        }

        return redirect()->route('loginPage');
    }
}
