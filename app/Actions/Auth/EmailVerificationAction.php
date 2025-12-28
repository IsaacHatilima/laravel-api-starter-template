<?php

namespace App\Actions\Auth;

use App\DTOs\Read\User\AuthResponseDTO;
use App\DTOs\Read\User\UserDTO;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class EmailVerificationAction
{
    /**
     * @return AuthResponseDTO|array{message: string}
     */
    public function execute(string $id): AuthResponseDTO|array
    {
        $user = User::query()->where('public_id', $id)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                ['User not found'],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            return ['message' => 'Email is already verified'];
        }

        $user->markEmailAsVerified();

        $token = JWTAuth::fromUser($user);

        return new AuthResponseDTO(
            user: UserDTO::fromModel($user->load('profile')),
            token: $token,
        );
    }
}
