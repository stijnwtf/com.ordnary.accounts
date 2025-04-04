<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\LoginActivity;
use App\Models\Token;
use App\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

        // Fetch the user's connected services
        $userServices = $user->services()
            ->where('status', 'active')
            ->with('client:id,name,client_id')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'service_id' => $service->client_id,
                    'service_name' => $service->client->name,
                    'first_login' => $service->first_login,
                    'last_used' => $service->last_used,
                ];
            });

        Log::info('User services fetched', [
            'user_id' => $user->id,
            'services_count' => $userServices->count()
        ]);

        return view('my-account.connections', [
            'user' => $user,
            'services' => $userServices,
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'language' => ['required', 'string', 'size:2', 'in:nl,en,de,fr'],
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;

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

    public function addRecoveryEmail(Request $request)
    {
        $request->validate([
            'recovery_email' => 'required|email|unique:users,recovery_email',
        ]);

        $user = Auth::user();
        $user->recovery_email = $request->recovery_email;
        $user->save();

        // Generate verification token
        $token = Str::random(64);
        $user->recovery_email_verification_token = $token;
        $user->save();

        // Send verification email
        Mail::send('emails.verify-recovery-email', ['token' => $token], function($message) use ($user) {
            $message->to($user->recovery_email);
            $message->subject('Verify Recovery Email');
        });

        return redirect()->route('account.security')->with('success', 'Recovery email added. Please check your email to verify it.');
    }

    public function verifyRecoveryEmail($token)
    {
        $user = User::where('recovery_email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('account.security')->with('error', 'Invalid verification token.');
        }

        $user->verifyRecoveryEmail();
        $user->recovery_email_verification_token = null;
        $user->save();

        return redirect()->route('account.security')->with('success', 'Recovery email verified successfully.');
    }

    public function removeRecoveryEmail(Request $request)
    {
        $user = Auth::user();
        $user->recovery_email = null;
        $user->recovery_email_verified = false;
        $user->recovery_email_verified_at = null;
        $user->save();

        return redirect()->route('account.security')->with('success', 'Recovery email removed successfully.');
    }

    /**
     * Alias for setup2FA to maintain route compatibility.
     *
     * @return \Illuminate\View\View
     */
    public function setupTwoFactor()
    {
        return $this->setup2FA();
    }

    public function setup2FA()
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        // Generate a new secret key
        $secret = $google2fa->generateSecretKey();

        // Generate QR code URL
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        // Store the secret temporarily in the session
        session(['2fa_setup_secret' => $secret]);

        return view('my-account.setup-2fa', [
            'qrCodeUrl' => $qrCodeUrl,
            'secret' => $secret,
            'user' => $user,
            'active_page' => 'security'
        ]);
    }

    /**
     * Alias for confirm2FA to maintain route compatibility.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function confirmTwoFactor(Request $request)
    {
        return $this->confirm2FA($request);
    }

    public function confirm2FA(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        $secret = session('2fa_setup_secret');

        if (!$secret) {
            return redirect()->route('account.setup-2fa')
                ->with('error', 'Setup session expired. Please try again.');
        }

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $request->code);

        if ($valid) {
            // Store the secret in the user's record
            $user->two_factor_secret = $secret;
            $user->two_factor_enabled = true;
            $user->two_factor_confirmed_at = now();
            $user->save();

            // Generate recovery codes
            $recoveryCodes = $this->generateRecoveryCodes($user);

            // Clear the setup secret from session
            session()->forget('2fa_setup_secret');

            return view('my-account.recovery-codes', [
                'user' => $user,
                'codes' => $recoveryCodes,
                'active_page' => 'security'
            ]);
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    public function disableTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = auth()->user();
        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($user->two_factor_secret, $request->code)) {
            $user->disableTwoFactor();

            return redirect()->route('account.security')
                ->with('success', 'Two-factor authentication has been disabled successfully.');
        }

        return back()->withErrors(['code' => 'Invalid verification code.']);
    }

    public function regenerateRecoveryCodes()
    {
        $user = auth()->user();
        $codes = $user->generateRecoveryCodes();

        return view('my-account.recovery-codes', [
            'user' => $user,
            'codes' => $codes,
            'active_page' => 'security'
        ]);
    }

    /**
     * Generate recovery codes for a user.
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    private function generateRecoveryCodes(User $user)
    {
        // Generate 8 random recovery codes
        $recoveryCodes = [];
        for ($i = 0; $i < 8; $i++) {
            $recoveryCodes[] = Str::random(10);
        }

        // Store the recovery codes in the user record
        $user->two_factor_recovery_codes = $recoveryCodes;
        $user->save();

        return $recoveryCodes;
    }

    /**
     * Generate and download a data export for the user (GDPR right to data portability)
     *
     * @return \Illuminate\Http\Response
     */
    public function exportUserData()
    {
        $user = Auth::user();

        Log::info('User data export requested', ['user_id' => $user->id]);

        // Collect user's personal data
        $userData = [
            'personal_info' => [
                'id' => $user->id,
                'numblio_upstream_id' => $user->numblio_id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'created_at' => $user->created_at->toIso8601String(),
                'updated_at' => $user->updated_at->toIso8601String(),
                'language' => $user->lang,
                'recovery_email' => $user->recovery_email,
                'has_two_factor' => !empty($user->two_factor_secret),
            ],
            'login_activities' => [],
            'connected_services' => [],
        ];

        // Get user's login activities
        $loginActivities = LoginActivity::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($loginActivities as $activity) {
            $userData['login_activities'][] = [
                'ip_address' => $activity->ip_address,
                'user_agent' => $activity->user_agent,
                'location' => $activity->location,
                'created_at' => $activity->created_at->toIso8601String(),
            ];
        }

        // Get user's connected services
        $userServices = $user->services()
            ->with('client:id,name,client_id')
            ->get();

        foreach ($userServices as $service) {
            $userData['connected_services'][] = [
                'service_name' => $service->client->name,
                'service_id' => $service->client->client_id,
                'first_login' => $service->first_login ? $service->first_login->toIso8601String() : null,
                'last_used' => $service->last_used ? $service->last_used->toIso8601String() : null,
                'status' => $service->status,
            ];
        }

        // Add GDPR metadata
        $userData['gdpr_metadata'] = [
            'data_controller' => 'Numblio',
            'data_controller_contact' => 'privacy@numblio.com',
            'export_date' => now()->toIso8601String(),
            'export_requested_by' => $user->email,
            'data_retention_policy' => 'User data is retained for as long as the account is active. After account deletion, data is kept for 30 days for backup purposes before being permanently deleted.',
        ];

        // Generate a JSON file
        $filename = 'numblio_data_export_' . $user->email . '_' . date('Y-m-d') . '.json';
        $jsonData = json_encode($userData, JSON_PRETTY_PRINT);

        Log::info('User data export completed', [
            'user_id' => $user->id,
            'filename' => $filename,
            'data_size' => strlen($jsonData)
        ]);

        return response()->streamDownload(function () use ($jsonData) {
            echo $jsonData;
        }, $filename, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Show account deletion confirmation page (GDPR right to be forgotten)
     *
     * @return \Illuminate\View\View
     */
    public function showDeleteAccount()
    {
        $user = Auth::user();

        return view('my-account.delete-account', [
            'user' => $user,
            'active_page' => 'security'
        ]);
    }

    /**
     * Process the account deletion request (GDPR right to be forgotten)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'confirmation' => 'required|in:DELETE'
        ]);

        $user = Auth::user();

        // Verify the password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Het ingevoerde wachtwoord is onjuist.'
            ]);
        }

        // Get user ID for logging
        $userId = $user->id;
        $userEmail = $user->email;

        // Start database transaction
        DB::beginTransaction();

        try {
            // Delete user data from various tables
            LoginActivity::where('user_id', $userId)->delete();
            UserService::where('user_id', $userId)->delete();
            // Delete user sessions
            DB::table('sessions')->where('user_id', $userId)->delete();

            // Schedule deletion of personal data
            // The user record itself will be anonymized immediately, but some data will be scheduled for deletion
            // to allow for legal compliance (e.g., financial records that must be kept)
            $user->name = 'Deleted User';
            $user->email = 'deleted_' . Str::random(10) . '@example.com';
            $user->password = Hash::make(Str::random(32));
            $user->is_deleted = true;
            $user->deleted_at = now();
            $user->recovery_email = null;
            $user->recovery_email_verified = false;
            $user->two_factor_secret = null;
            $user->two_factor_recovery_codes = null;
            $user->save();

            // Log the account deletion
            Log::info('User account deleted (GDPR right to be forgotten)', [
                'user_id' => $userId,
                'email' => $userEmail,
                'ip_address' => $request->ip()
            ]);

            // Commit transaction
            DB::commit();

            // Log the user out
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect to confirmation page
            return redirect()->route('account.deletion-confirmed');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            Log::error('Failed to delete user account', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'general' => 'Er is een fout opgetreden bij het verwijderen van uw account. Probeer het later opnieuw of neem contact op met ondersteuning.'
            ]);
        }
    }

    /**
     * Show account deletion confirmation page
     *
     * @return \Illuminate\View\View
     */
    public function showDeletionConfirmed()
    {
        return view('account.deletion-confirmed');
    }
}
