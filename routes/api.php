<?php

use App\Http\Controllers\API\UserServiceController;
use App\Http\Controllers\SSO\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Hier kun je alle API-routes registreren. Deze routes krijgen automatisch
| de "api" middleware groep toegewezen en hebben geen CSRF-verificatie.
|
*/

Route::prefix('v1')->group(function () {
    // Token validation endpoint
    Route::post('/validate-token', [AuthController::class, 'validateToken']);
});

// User API routes with authentication
Route::middleware(['auth'])->prefix('user')->group(function () {
    // Connected services
    Route::get('/services', [UserServiceController::class, 'index']);
});

// User route with auth and CSRF protection
Route::middleware(['auth', 'web'])->prefix('user')->group(function () {
    Route::post('/unlink-service', [UserServiceController::class, 'unlinkService']);
});
