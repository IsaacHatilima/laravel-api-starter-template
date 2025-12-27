<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\EndAllSessionsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TwoFactorManagerController;
use App\Http\Middleware\EnsureJwtVersionIsValid;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
    Route::post('forgot-password', ForgotPasswordController::class);
    Route::get('set-password', [ResetPasswordController::class, 'verify'])->name('password.reset');
    Route::post('set-password', [ResetPasswordController::class, 'change']);
    Route::get('email-verification', EmailVerificationController::class)
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('refresh-token', RefreshTokenController::class);
});

// Protected authentication routes
Route::middleware(['auth:api', EnsureJwtVersionIsValid::class])->prefix('auth')->group(function () {
    Route::post('logout', LogoutController::class);
    Route::post('end-all-sessions', EndAllSessionsController::class);
    Route::get('me', MeController::class);

    // Two-factor authentication routes
    Route::post('2fa-enable', [TwoFactorManagerController::class, 'enable']);
    Route::post('2fa-confirm', [TwoFactorManagerController::class, 'confirm']);
    Route::post('2fa-disable', [TwoFactorManagerController::class, 'disable']);
});
