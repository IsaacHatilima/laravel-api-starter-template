<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TwoFactorManagerController;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
    Route::get('email-verification', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('refresh-token', RefreshTokenController::class);
});

// Protected authentication routes
Route::middleware('auth:api')->prefix('auth')->group(function () {
    Route::post('logout', LogoutController::class);
    Route::get('me', MeController::class);

    // Two-factor authentication routes
    Route::post('2fa-enable', [TwoFactorManagerController::class, 'enable']);
    Route::post('2fa-confirm', [TwoFactorManagerController::class, 'confirm']);
    Route::post('2fa-disable', [TwoFactorManagerController::class, 'disable']);
});
