<?php

Route::prefix('v1')->group(function () {
    require __DIR__.'/V1/auth.php';
    require __DIR__.'/V1/settings.php';
});
