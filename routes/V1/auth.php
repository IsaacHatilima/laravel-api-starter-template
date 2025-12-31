<?php

use App\Http\Controllers\V1\Auth\EmailVerificationController;
use App\Http\Controllers\V1\Auth\EndAllSessionsController;
use App\Http\Controllers\V1\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\LogoutController;
use App\Http\Controllers\V1\Auth\MeController;
use App\Http\Controllers\V1\Auth\RefreshTokenController;
use App\Http\Controllers\V1\Auth\RegisterController;
use App\Http\Controllers\V1\Auth\ResetPasswordController;
use App\Http\Controllers\V1\Auth\TwoFactorLoginController;
use App\Http\Controllers\V1\Settings\TwoFactorManagerController;
use App\Http\Middleware\AuthRateLimiterMiddleware;
use App\Http\Middleware\EnsureJwtVersionIsValidMiddleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

// Public authentication routes
Route::prefix('auth')
    ->middleware([AuthRateLimiterMiddleware::class, StartSession::class])
    ->group(function () {
        Route::post('register', RegisterController::class);
        Route::post('login', LoginController::class);
        Route::post('two-factor-login', TwoFactorLoginController::class);
        Route::post('forgot-password', ForgotPasswordController::class);
        Route::get('set-password', [ResetPasswordController::class, 'verify'])->name('password.reset');
        Route::post('set-password', [ResetPasswordController::class, 'change']);
        Route::get('email-verification', EmailVerificationController::class)
            ->middleware('signed')
            ->name('verification.verify');
        Route::post('refresh-token', RefreshTokenController::class);
    });

// Protected authentication routes
Route::middleware(['auth:api', EnsureJwtVersionIsValidMiddleware::class])->prefix('auth')->group(function () {
    Route::post('logout', LogoutController::class);
    Route::post('end-all-sessions', EndAllSessionsController::class);
    Route::get('me', MeController::class);

    // Two-factor authentication routes
    Route::post('2fa-enable', [TwoFactorManagerController::class, 'enable']);
    Route::post('2fa-confirm', [TwoFactorManagerController::class, 'confirm']);
    Route::post('2fa-disable', [TwoFactorManagerController::class, 'disable']);
    Route::post('2fa-recovery-codes', [TwoFactorManagerController::class, 'generateRecoveryCodes']);
});
