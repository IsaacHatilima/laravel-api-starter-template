<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RefreshTokenAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class RefreshTokenController extends Controller
{
    public function __construct(private readonly RefreshTokenAction $refreshTokenAction)
    {
    }

    public function __invoke(): JsonResponse
    {
        $token = request()->bearerToken();

        if (! $token) {
            throw new RuntimeException('No bearer token provided');
        }

        $token = $this->refreshTokenAction->execute($token);

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
        ]);
    }
}
