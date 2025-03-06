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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        return view('my-account.index', [
            'user' => $user,
            'recaptcha_site_key' => config('services.recaptcha.site_key')
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
        ]);

        $user->name = $request->name;
        
        // Only update email if it has changed
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            // Here you could add email verification logic if needed
        }

        $user->save();

        Log::info('User profile updated', ['user_id' => $user->id]);
        
        return redirect()->route('my-account')->with('status', 'Profiel succesvol bijgewerkt!');
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
        
        return redirect()->route('my-account')->with('status', 'Wachtwoord succesvol bijgewerkt!');
    }
} 