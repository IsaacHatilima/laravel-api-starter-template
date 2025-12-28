<?php

namespace App\DTOs\Auth;

use App\DTOs\BaseDTO;
use App\DTOs\User\UserDTO;

/**
 * @extends BaseDTO<string, mixed>
 */
final readonly class AuthResponseDTO extends BaseDTO
{
    public function __construct(
        public UserDTO $user,
        public string $token,
        public string $tokenType = 'bearer',
    ) {
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            'token' => $this->token,
            'token_type' => $this->tokenType,
        ];
    }
}
