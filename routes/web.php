<?php

use App\Http\Controllers\SSO\AuthController;
use App\Http\Controllers\SSO\ClientController;
use App\Http\Controllers\SSO\UserController;
use App\Http\Controllers\SSO\SessionController;
use App\Http\Controllers\SSO\DashboardController;
use App\Http\Controllers\SSO\SettingsController;
use App\Http\Controllers\SSO\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password-sent', [AuthController::class, 'showSentForm'])->name('password.sent');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/reset-success', [AuthController::class, 'showSuccessForm'])->name('password.success');

Route::get('/admin/login', [ClientController::class, 'showLoginForm'])->name('admin.loginForm');
Route::get('/admin/logout', [ClientController::class, 'logout'])->name('admin.logout');
Route::post('/admin/login', [ClientController::class, 'login'])->name('adminloginprocess');

// Admin routes for managing clients
Route::middleware(['adminAuth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard route
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('clients', ClientController::class);
    Route::put('/clients/{client}/regenerate-credentials', [ClientController::class, 'regenerateCredentials'])
        ->name('clients.regenerate-credentials');
    
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
    
    // Admin routes for activity logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('/activity-logs/{activityLog}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::post('/activity-logs/destroy-all', [ActivityLogController::class, 'destroyAll'])->name('activity-logs.destroy-all');
});
