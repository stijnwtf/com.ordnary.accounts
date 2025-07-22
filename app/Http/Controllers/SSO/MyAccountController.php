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
     * Show the user subscriptions page.
     *
     * @return \Illuminate\View\View
     */
    public function subscriptions()
    {
        $user = Auth::user();
        
        // Example data structure for subscriptions
        // In a real implementation, you would fetch this from your subscription database/service
        $subscriptions = [
            [
                'id' => 'sub_123456',
                'product_name' => 'MockScene Pro',
                'status' => 'active',
                'started_at' => now()->subMonths(2),
                'expires_at' => now()->addMonths(10),
                'next_billing' => now()->addMonth(),
                'plan' => 'Yearly',
                'price' => '€99.00',
                'features' => ['Unlimited projects', 'Advanced components', 'API access'],
                'usage' => [
                    'projects' => ['used' => 12, 'total' => 'Unlimited'],
                    'storage' => ['used' => '1.2 GB', 'total' => '10 GB', 'percentage' => 12],
                    'api_calls' => ['used' => 850, 'total' => 10000, 'percentage' => 8.5]
                ]
            ],
            // Example of another product
            [
                'id' => 'sub_789012',
                'product_name' => 'Numblio Forms',
                'status' => 'active',
                'started_at' => now()->subWeeks(3),
                'expires_at' => null, // null means it renews automatically
                'next_billing' => now()->addDays(7),
                'plan' => 'Monthly',
                'price' => '€9.99',
                'features' => ['50 form submissions', 'Basic templates', 'Email notifications'],
                'usage' => [
                    'submissions' => ['used' => 23, 'total' => 50, 'percentage' => 46],
                    'forms' => ['used' => 3, 'total' => 5, 'percentage' => 60]
                ]
            ]
        ];

        // In production, you would do something like this:
        // $subscriptions = SubscriptionService::getUserSubscriptions($user->id);
        
        Log::info('User subscriptions fetched', [
            'user_id' => $user->id,
            'subscriptions_count' => count($subscriptions)
        ]);

        return view('my-account.subscriptions', [
            'user' => $user,
            'subscriptions' => $subscriptions,
            'active_page' => 'subscriptions'
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'language' => 'required|string|size:2|in:' . implode(',', config('app.available_locales', ['en', 'nl'])),
        ]);
        
        $user = auth()->user();
        
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'lang' => $request->language,
        ]);
        
        // Set the application language
        app()->setLocale($request->language);
        session(['language' => $request->language]);
        
        return redirect()->back()->with('success', lang('notifications.success', [], 'profile'));
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
     * Generate and download a comprehensive data export for the user (GDPR right to data portability)
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function exportUserData()
    {
        $user = Auth::user();

        Log::info('Comprehensive user data export requested', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'timestamp' => now()->toIso8601String()
        ]);

        try {
            // Export format header with comprehensive metadata
            $exportData = [
                'export_metadata' => [
                    'export_version' => '2.0',
                    'format_version' => 'GDPR-2024.1',
                    'generated_at' => now()->toIso8601String(),
                    'generated_for' => $user->email,
                    'user_id' => $user->id,
                    'data_controller' => [
                        'name' => 'Numblio B.V.',
                        'address' => 'Hoofdstraat 123, 1234 AB Amsterdam, Netherlands',
                        'contact_email' => 'privacy@numblio.com',
                        'dpo_email' => 'dpo@numblio.com',
                        'website' => 'https://numblio.com'
                    ],
                    'legal_basis' => 'GDPR Article 20 - Right to data portability',
                    'data_categories' => [
                        'Identity Data', 'Contact Data', 'Technical Data', 
                        'Usage Data', 'Marketing and Communications Data', 
                        'Security Data', 'Activity Logs'
                    ],
                    'retention_policy' => [
                        'active_account' => 'Data retained while account is active',
                        'deleted_account' => '30 days retention for backup and legal compliance',
                        'legal_obligations' => 'Some data may be retained longer for legal compliance'
                    ]
                ],

                // Personal Information
                'personal_information' => [
                    'basic_info' => [
                        'internal_id' => $user->id,
                        'numblio_id' => $user->numblio_id,
                        'username' => $user->username,
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'full_name' => $user->name,
                        'email_address' => $user->email,
                        'preferred_language' => $user->lang,
                        'account_created' => $user->created_at->toIso8601String(),
                        'last_updated' => $user->updated_at->toIso8601String(),
                        'timezone' => config('app.timezone'),
                        'email_verified' => !is_null($user->email_verified_at),
                        'email_verified_at' => $user->email_verified_at?->toIso8601String(),
                    ],
                    'recovery_information' => [
                        'recovery_email' => $user->recovery_email,
                        'recovery_email_verified' => $user->recovery_email_verified,
                        'recovery_email_verified_at' => $user->recovery_email_verified_at?->toIso8601String(),
                    ],
                    'security_settings' => [
                        'is_admin' => $user->is_admin,
                        'two_factor_enabled' => $user->two_factor_enabled,
                        'two_factor_confirmed_at' => $user->two_factor_confirmed_at?->toIso8601String(),
                        'has_recovery_codes' => !empty($user->two_factor_recovery_codes),
                        'recovery_codes_count' => $user->two_factor_recovery_codes ? count($user->two_factor_recovery_codes) : 0,
                    ]
                ],

                // Account Activity
                'account_activity' => [],

                // Login History
                'login_history' => [],

                // Connected Services
                'connected_services' => [],

                // Active Sessions
                'active_sessions' => [],

                // Activity Logs
                'system_activity_logs' => [],

                // Statistics
                'usage_statistics' => []
            ];

            // Collect comprehensive login activities
            $loginActivities = LoginActivity::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $loginStats = [
                'total_logins' => $loginActivities->count(),
                'successful_logins' => $loginActivities->where('status', 'success')->count(),
                'failed_logins' => $loginActivities->where('status', 'failed')->count(),
                'unique_ip_addresses' => $loginActivities->pluck('ip_address')->unique()->count(),
                'unique_locations' => $loginActivities->whereNotNull('location')->pluck('location')->unique()->count(),
                'unique_devices' => $loginActivities->whereNotNull('device_type')->pluck('device_type')->unique()->count(),
                'unique_browsers' => $loginActivities->whereNotNull('browser')->pluck('browser')->unique()->count(),
                'last_login' => $loginActivities->first()?->created_at?->toIso8601String(),
                'first_login' => $loginActivities->last()?->created_at?->toIso8601String(),
            ];

            foreach ($loginActivities as $activity) {
                $exportData['login_history'][] = [
                    'timestamp' => $activity->created_at->toIso8601String(),
                    'ip_address' => $activity->ip_address,
                    'location' => $activity->location,
                    'device_info' => [
                        'user_agent' => $activity->user_agent,
                        'device_type' => $activity->device_type,
                        'browser' => $activity->browser,
                        'platform' => $activity->platform,
                    ],
                    'status' => $activity->status,
                    'session_duration' => null // Could be calculated if we track session end
                ];
            }

            // Collect connected services with detailed information
            $userServices = $user->services()
                ->with('client')
                ->get();

            foreach ($userServices as $service) {
                $exportData['connected_services'][] = [
                    'service_info' => [
                        'name' => $service->client->name,
                        'client_id' => $service->client->client_id,
                        'service_type' => 'OAuth2 Application',
                    ],
                    'connection_details' => [
                        'first_connected' => $service->first_login ? $service->first_login->toIso8601String() : null,
                        'last_used' => $service->last_used ? $service->last_used->toIso8601String() : null,
                        'status' => $service->status,
                        'total_usage_time' => null, // Could be calculated
                    ],
                    'permissions_granted' => [
                        'profile_access' => true,
                        'email_access' => true,
                    ]
                ];
            }

            // Collect active sessions
            $activeSessions = $user->userSessions()
                ->with('client')
                ->where('expires_at', '>', now())
                ->get();

            foreach ($activeSessions as $session) {
                $exportData['active_sessions'][] = [
                    'session_info' => [
                        'session_id' => $session->id,
                        'client_name' => $session->client->name ?? 'Unknown',
                        'token_type' => 'Bearer',
                        'created_at' => $session->created_at->toIso8601String(),
                        'expires_at' => $session->expires_at->toIso8601String(),
                        'time_until_expiry' => $session->expires_at->diffForHumans(),
                        'is_active' => $session->expires_at > now(),
                    ],
                    'security_info' => [
                        'token_prefix' => substr($session->access_token ?? '', 0, 10) . '...',
                        'jti' => $session->jti,
                    ]
                ];
            }

            // Generate comprehensive usage statistics
            $exportData['usage_statistics'] = [
                'account_age_days' => $user->created_at->diffInDays(now()),
                'login_statistics' => $loginStats,
                'session_statistics' => [
                    'total_sessions_created' => $user->userSessions()->count(),
                    'currently_active_sessions' => $activeSessions->count(),
                    'expired_sessions' => $user->userSessions()->where('expires_at', '<', now())->count(),
                ],
                'security_statistics' => [
                    'has_two_factor' => $user->two_factor_enabled,
                    'has_recovery_email' => !empty($user->recovery_email),
                    'email_verified' => !is_null($user->email_verified_at),
                ]
            ];

            // Generate export metadata summary
            $totalDataPoints = $loginActivities->count() + $userServices->count() + 
                             $activeSessions->count();

            $exportData['export_metadata']['summary'] = [
                'total_data_points' => $totalDataPoints,
                'categories_included' => count($exportData) - 1, // Exclude metadata
                'export_size_estimate' => strlen(json_encode($exportData)) . ' bytes',
                'processing_time' => 'Generated in real-time',
            ];

            // Log successful export
            ActivityLog::log(
                'data_export', 
                'User requested comprehensive data export', 
                $user, 
                $user
            );

            // Generate filename with timestamp
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "numblio_comprehensive_export_{$user->username}_{$timestamp}.json";
            
            $jsonData = json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            Log::info('Comprehensive user data export completed successfully', [
                'user_id' => $user->id,
                'filename' => $filename,
                'data_size_bytes' => strlen($jsonData),
                'data_points' => $totalDataPoints,
                'processing_time' => microtime(true) - LARAVEL_START
            ]);

            return response()->streamDownload(function () use ($jsonData) {
                echo $jsonData;
            }, $filename, [
                'Content-Type' => 'application/json; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);

        } catch (\Exception $e) {
            Log::error('Data export failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Er is een fout opgetreden bij het genereren van de gegevensexport.',
                'message' => 'Probeer het later opnieuw of neem contact op met de ondersteuning.'
            ], 500);
        }
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
