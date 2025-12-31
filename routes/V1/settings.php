<?php

use App\Http\Controllers\V1\Settings\UpdatePasswordController;
use App\Http\Controllers\V1\Settings\UpdateProfileController;
use App\Http\Middleware\EnsureJwtVersionIsValidMiddleware;

Route::middleware(['auth:api', EnsureJwtVersionIsValidMiddleware::class])->prefix('settings')->group(function () {
    Route::post('update-profile', UpdateProfileController::class);
    Route::post('update-password', UpdatePasswordController::class);
});
