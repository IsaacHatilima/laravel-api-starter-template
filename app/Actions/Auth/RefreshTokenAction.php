<?php

namespace App\Actions\Auth;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

readonly class RefreshTokenAction
{
    public function execute(string $token): string
    {
        return JWTAuth::setToken($token)->refresh();
    }
}
