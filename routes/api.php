<?php

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
    
    // New token management endpoints
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/revoke-token', [AuthController::class, 'revokeToken']);
});
