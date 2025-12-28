<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;

final readonly class ConfirmTwoFactorAction
{
    public function __construct(
        private ConfirmTwoFactorAuthentication $confirmTwoFactor
    ) {
    }

    /**
     * Confirm two-factor authentication for the user.
     *
     * @throws ValidationException
     */
    public function execute(User $user, string $code): void
    {
        ($this->confirmTwoFactor)($user, $code);
    }
}
