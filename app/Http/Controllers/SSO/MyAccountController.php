<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class MyAccountController extends Controller
{
    /**
     * Show the user's account page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Redirect naar de profielpagina
        return redirect()->route('account.profile');
    }

    /**
     * Show the user's profile page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();
        return view('my-account.profile', [
            'user' => $user,
            'recaptcha_site_key' => config('services.recaptcha.site_key'),
            'active_page' => 'profile'
        ]);
    }

    /**
     * Show the password change page.
     *
     * @return \Illuminate\View\View
     */
    public function password()
    {
        $user = Auth::user();
        return view('my-account.password', [
            'user' => $user,
            'recaptcha_site_key' => config('services.recaptcha.site_key'),
            'active_page' => 'password'
        ]);
    }

    /**
     * Show the security settings page.
     *
     * @return \Illuminate\View\View
     */
    public function security()
    {
        $user = Auth::user();
        return view('my-account.security', [
            'user' => $user,
            'recaptcha_site_key' => config('services.recaptcha.site_key'),
            'active_page' => 'security'
        ]);
    }

    /**
     * Show the notifications settings page.
     *
     * @return \Illuminate\View\View
     */
    public function notifications()
    {
        $user = Auth::user();
        return view('my-account.notifications', [
            'user' => $user,
            'recaptcha_site_key' => config('services.recaptcha.site_key'),
            'active_page' => 'notifications'
        ]);
    }

    /**
     * Show the connected services page.
     *
     * @return \Illuminate\View\View
     */
    public function connections()
    {
        $user = Auth::user();
        return view('my-account.connections', [
            'user' => $user,
            'recaptcha_site_key' => config('services.recaptcha.site_key'),
            'active_page' => 'connections'
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'language' => ['required', 'string', 'size:2', 'in:nl,en,de,fr'],
        ]);

        $user->name = $request->name;
        
        // Only update email if it has changed
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            // Here you could add email verification logic if needed
        }
        
        // Update language preference
        $user->lang = $request->language;

        $user->save();

        Log::info('User profile updated', ['user_id' => $user->id]);
        
        return redirect()->route('account.profile')->with('status', 'Profiel succesvol bijgewerkt!');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Het huidige wachtwoord is onjuist.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        Log::info('User password updated', ['user_id' => $user->id]);
        
        return redirect()->route('account.password')->with('status', 'Wachtwoord succesvol bijgewerkt!');
    }
} 