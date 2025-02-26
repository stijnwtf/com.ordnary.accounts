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
    // Token validatie endpoint
    Route::post('/validate-token', [AuthController::class, 'validateToken']);
});
