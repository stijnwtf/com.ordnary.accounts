<?php

use App\Http\Controllers\SSO\AuthController;
use App\Http\Controllers\SSO\ClientController;
use App\Http\Controllers\SSO\UserController;
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
    Route::resource('clients', ClientController::class);
    Route::put('/clients/{client}/regenerate-credentials', [ClientController::class, 'regenerateCredentials'])
        ->name('clients.regenerate-credentials');
    
    // Admin routes for managing users
    Route::resource('users', UserController::class);
});
