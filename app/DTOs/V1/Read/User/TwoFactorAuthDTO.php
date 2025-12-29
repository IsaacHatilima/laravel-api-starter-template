<?php

namespace App\DTOs\V1\Read\User;

use App\Models\User;

final readonly class TwoFactorAuthDTO
{
    public function __construct(
        public ?User $user,
        public ?string $token,
        public bool $requiresTwoFactor,
    ) {
    }
}
