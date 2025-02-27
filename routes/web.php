<?php

use App\Http\Controllers\SSO\AuthController;
use App\Http\Controllers\SSO\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])->name('forgot-password');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/admin/login', [ClientController::class, 'showLoginForm'])->name('admin.loginForm');
Route::get('/admin/logout', [ClientController::class, 'logout'])->name('admin.logout');
Route::post('/admin/login', [ClientController::class, 'adminloginprocess']);

// Admin routes for managing clients
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('clients', ClientController::class);
    Route::put('/clients/{client}/regenerate-credentials', [ClientController::class, 'regenerateCredentials'])
        ->name('clients.regenerate-credentials');
});
