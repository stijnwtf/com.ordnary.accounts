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
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout/simple', function (Illuminate\Http\Request $request) {
    return app(AuthController::class)->logout($request->merge(['skip_sso' => true]));
})->name('logout.simple');
Route::get('/logout/process', [AuthController::class, 'processLogout'])->name('logout.process');

// Banned/Suspended Account route
Route::get('/suspended', function () {
    return view('banned');
})->name('banned');

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
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

// Email Verification Routes
Route::middleware('throttle:6,1')->group(function () {
    Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
        ->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
});

// My Account routes
Route::prefix('app')->middleware(['auth', 'verified'])->group(function () {
    // Bestaande route als redirect naar account/profile
    Route::get('/', [MyAccountController::class, 'index'])->name('my-account');

    // Nieuwe routes voor aparte pagina's
    Route::get('/profile', [MyAccountController::class, 'profile'])->name('account.profile');
    Route::get('/password', [MyAccountController::class, 'password'])->name('account.password');
    Route::get('/security', [MyAccountController::class, 'security'])->name('account.security');
    Route::get('/notifications', [MyAccountController::class, 'notifications'])->name('account.notifications');
    Route::get('/connections', [MyAccountController::class, 'connections'])->name('account.connections');
    Route::get('/subscriptions', [MyAccountController::class, 'subscriptions'])->name('account.subscriptions');

    // GDPR Data Portability route
    Route::get('/export-data', [MyAccountController::class, 'exportUserData'])->name('account.export-data');

    // GDPR Right to be Forgotten routes
    Route::get('/delete-account', [MyAccountController::class, 'showDeleteAccount'])->name('account.delete');
    Route::post('/delete-account', [MyAccountController::class, 'deleteAccount'])->name('account.delete.process');
    Route::get('/deletion-confirmed', [MyAccountController::class, 'showDeletionConfirmed'])->name('account.deletion-confirmed');

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

// Legacy admin routes - redirect to new Filament admin panel
Route::middleware(['adminAuth', 'admin'])->prefix('admin')->group(function () {
    // Redirect dashboard to Filament admin
    Route::get('/', function () {
        return redirect('/beheer');
    })->name('admin.dashboard');

    // Redirect legacy resource routes to Filament admin
    Route::get('/clients', function () {
        return redirect('/beheer/clients');
    });
    Route::get('/users', function () {
        return redirect('/beheer/users');
    });
    Route::get('/sessions', function () {
        return redirect('/beheer/user-sessions');
    });

    // Keep API-like routes that might be used by external systems
    Route::put('/clients/{client}/regenerate-credentials', [ClientController::class, 'regenerateCredentials'])->name('clients.regenerate-credentials');
    Route::post('/sessions/bulk-destroy', [SessionController::class, 'bulkDestroy'])->name('sessions.bulk-destroy');
    Route::post('/sessions/destroy-expired', [SessionController::class, 'destroyExpired'])->name('sessions.destroy-expired');

    // Keep settings routes if they have special functionality
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

// 2FA Routes
Route::get('/2fa', [AuthController::class, 'show2FAForm'])->name('2fa');
Route::post('/2fa/verify', [AuthController::class, 'verify2FA'])->name('2fa.verify');
Route::get('/2fa/recovery', [AuthController::class, 'showRecoveryForm'])->name('2fa.recovery');
Route::post('/2fa/recovery', [AuthController::class, 'verifyRecoveryCode'])->name('2fa.recovery.verify');

// Language switch route
Route::get('/language/{language}', [LanguageController::class, 'switchLanguage'])->name('language.switch');
