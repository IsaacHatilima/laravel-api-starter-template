<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final readonly class LoginUserAction
{
    /**
     * @throws ValidationException
     */
    public function execute(LoginDTO $dto): string
    {
        $user = User::query()->where('email', $dto->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                ['The provided credentials are incorrect'],
            ]);
        }

        if ($user->is_active === false) {
            throw ValidationException::withMessages([
                ['Account is not active'],
            ]);
        }

        if ($user->email_verified_at === null) {
            throw ValidationException::withMessages([
                ['Email is not verified'],
            ]);
        }

        $token = JWTAuth::attempt([
            'email' => $dto->email,
            'password' => $dto->password,
        ]);

        if (! $token) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect'],
            ]);
        }

        return $token;
    }
}
