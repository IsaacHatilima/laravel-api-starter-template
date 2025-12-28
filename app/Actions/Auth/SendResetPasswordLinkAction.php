<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\ForgotPasswordDTO;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final readonly class SendResetPasswordLinkAction
{
    /**
     * @throws ValidationException
     */
    public function execute(ForgotPasswordDTO $dto): string
    {
        $status = Password::broker()->sendResetLink(
            $dto->toArray()
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return __($status);
    }
}
