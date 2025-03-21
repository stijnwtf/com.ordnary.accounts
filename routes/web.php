<?php

use App\Http\Controllers\SSO\AuthController;
use App\Http\Controllers\SSO\ClientController;
use App\Http\Controllers\SSO\UserController;
use App\Http\Controllers\SSO\SessionController;
use App\Http\Controllers\SSO\DashboardController;
use App\Http\Controllers\SSO\SettingsController;
use App\Http\Controllers\SSO\ActivityLogController;
use App\Http\Controllers\SSO\NotificationController;
use App\Http\Controllers\SSO\MyAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/admin/login', [ClientController::class, 'login'])->name('adminloginprocess');
});

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.forgot');
Route::get('/reset-password-sent', [AuthController::class, 'showSentForm'])->name('password.sent');
Route::get('/pw/callback/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::get('/reset-success', [AuthController::class, 'showSuccessForm'])->name('password.success');
Route::get('/admin/login', [ClientController::class, 'showLoginForm'])->name('admin.loginForm');
Route::get('/admin/logout', [ClientController::class, 'logout'])->name('admin.logout');

// My Account routes
Route::prefix('account')->middleware(['auth'])->group(function () {
    // Bestaande route als redirect naar account/profile
    Route::get('/', [MyAccountController::class, 'index'])->name('my-account');

    // Nieuwe routes voor aparte pagina's
    Route::get('/profile', [MyAccountController::class, 'profile'])->name('account.profile');
    Route::get('/password', [MyAccountController::class, 'password'])->name('account.password');
    Route::get('/security', [MyAccountController::class, 'security'])->name('account.security');
    Route::get('/notifications', [MyAccountController::class, 'notifications'])->name('account.notifications');
    Route::get('/connections', [MyAccountController::class, 'connections'])->name('account.connections');

    // API routes voor het updaten van gegevens
    Route::post('/profile', [MyAccountController::class, 'updateProfile'])->name('my-account.profile');
    Route::post('/password', [MyAccountController::class, 'updatePassword'])->name('my-account.password');
    Route::post('/security/add-recovery-email', [MyAccountController::class, 'addRecoveryEmail'])->name('account.security.add-recovery-email');
    Route::post('/security/remove-recovery-email', [MyAccountController::class, 'removeRecoveryEmail'])->name('account.security.remove-recovery-email');
    Route::get('/security/verify-recovery-email/{token}', [MyAccountController::class, 'verifyRecoveryEmail'])->name('account.security.verify-recovery-email');

    // 2FA Routes
    Route::get('/security/2fa/setup', [MyAccountController::class, 'setupTwoFactor'])->name('account.security.setup-2fa');
    Route::post('/security/2fa/confirm', [MyAccountController::class, 'confirmTwoFactor'])->name('account.security.confirm-2fa');
    Route::post('/security/2fa/disable', [MyAccountController::class, 'disableTwoFactor'])->name('account.security.disable-2fa');
    Route::post('/security/2fa/recovery-codes', [MyAccountController::class, 'regenerateRecoveryCodes'])->name('account.security.regenerate-recovery-codes');
});

// Admin routes for managing clients
Route::middleware(['adminAuth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard route
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('clients', ClientController::class);
    Route::put('/clients/{client}/regenerate-credentials', [ClientController::class, 'regenerateCredentials'])->name('clients.regenerate-credentials');
    // Admin routes for managing users
    Route::resource('users', UserController::class);
    // Admin routes for managing sessions
    Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/{session}', [SessionController::class, 'show'])->name('sessions.show');
    Route::delete('/sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');
    Route::post('/sessions/bulk-destroy', [SessionController::class, 'bulkDestroy'])->name('sessions.bulk-destroy');
    Route::post('/sessions/destroy-expired', [SessionController::class, 'destroyExpired'])->name('sessions.destroy-expired');
    // Admin routes for settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('admin.settings.clear-cache');
});

// Test route for welcome email (only in local environment)
if (app()->environment('local')) {
    Route::get('/test-welcome-email', function () {
        $user = \App\Models\User::first();
        if (!$user) {
            return 'No users found. Please create a user first.';
        }

        \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user));

        return 'Welcome email sent to ' . $user->email;
    });
}
