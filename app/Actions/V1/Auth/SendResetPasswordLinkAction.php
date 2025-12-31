<?php

namespace App\Actions\V1\Auth;

use App\DTOs\V1\Command\Auth\ForgotPasswordRequestDTO;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final readonly class SendResetPasswordLinkAction
{
    /**
     * @throws ValidationException
     */
    public function execute(ForgotPasswordRequestDTO $dto): void
    {
        $status = Password::broker()->sendResetLink(
            $dto->toArray()
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
    }
}
