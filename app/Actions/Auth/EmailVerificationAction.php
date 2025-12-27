<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\AuthResponseDTO;
use App\DTOs\User\UserDTO;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

readonly class EmailVerificationAction
{
    public function execute(string $id): AuthResponseDTO
    {
        $user = User::query()->where('public_id', $id)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                ['User not found.'],
            ]);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        $token = JWTAuth::fromUser($user);

        return new AuthResponseDTO(
            user: UserDTO::fromModel($user->load('profile')),
            token: $token,
        );
    }
}
