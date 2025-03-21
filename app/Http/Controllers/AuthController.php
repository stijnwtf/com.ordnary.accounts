<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use PragmaRX\Google2FA\Google2FA;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->handleSuccessfulLogin($request);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

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

            return $this->handleSuccessfulLogin($request);
        }

        return back()->withErrors([
            'code' => 'Invalid verification code.',
        ]);
    }

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

            return $this->handleSuccessfulLogin($request);
        }

        return back()->withErrors([
            'code' => 'Invalid recovery code.',
        ]);
    }

    private function handleSuccessfulLogin(Request $request)
    {
        $user = Auth::user();

        // Check if 2FA is enabled
        if ($user->hasTwoFactorEnabled()) {
            // Store user ID in session for 2FA verification
            session(['2fa_user_id' => $user->id]);
            
            // If this is a direct login, store that information
            if ($request->boolean('direct_login')) {
                session(['direct_login' => true]);
            } else {
                // For client login, store client information
                session([
                    'client_id' => $request->client_id,
                    'redirect_uri' => $request->redirect_uri,
                    'client_name' => $request->client_name
                ]);
            }
            
            // Log the user out but keep the session
            Auth::logout();
            
            // Redirect to 2FA verification page
            return redirect()->route('2fa');
        }

        // Handle client redirect if present
        if (session()->has('client_id') && session()->has('redirect_uri')) {
            $client = Client::find(session('client_id'));
            if ($client && $client->redirect_uri === session('redirect_uri')) {
                $token = $client->generateAccessToken(Auth::user());
                return redirect()->away(session('redirect_uri') . '?token=' . $token);
            }
        }

        // For direct login, redirect to the account page
        if (session()->has('direct_login')) {
            return redirect()->intended(route('my-account'));
        }

        // Default redirect to account page
        return redirect()->intended(route('my-account'));
    }
} 