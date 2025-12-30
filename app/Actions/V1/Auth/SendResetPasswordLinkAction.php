<?php

namespace App\Actions\V1\Auth;

use App\DTOs\V1\Command\Auth\ForgotPasswordDTO;
use App\Enums\ActionStatusEnum;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final readonly class SendResetPasswordLinkAction
{
    /**
     * @throws ValidationException
     */
    public function execute(ForgotPasswordDTO $dto): ActionStatusEnum
    {
        $status = Password::broker()->sendResetLink(
            $dto->toArray()
        );

        if ($status !== Password::RESET_LINK_SENT) {
            return ActionStatusEnum::FAILED;
        }

        return ActionStatusEnum::SUCCESS;
    }
}
