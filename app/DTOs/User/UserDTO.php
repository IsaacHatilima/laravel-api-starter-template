<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;
use App\Models\Profile;
use App\Models\User;

/**
 * @extends BaseDTO<string, mixed>
 */
readonly class UserDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $email,
        public ?string $emailVerifiedAt,
        public bool $twoFactorEnabled,
        public ?ProfileDTO $profile,
        public ?string $createdAt,
        public ?string $updateAt,
    ) {
    }

    public static function fromModel(User $user): self
    {
        /** @var Profile|null $profile */
        $profile = $user->relationLoaded('profile')
            ? $user->profile
            : null;

        return new self(
            id: $user->public_id,
            email: $user->email,
            emailVerifiedAt: $user->email_verified_at?->toISOString(),
            twoFactorEnabled: (bool) $user->two_factor_confirmed_at,
            profile: $profile !== null
                ? ProfileDTO::fromModel($profile)
                : null,
            createdAt: $user->created_at?->toISOString(),
            updateAt: $user->updated_at?->toISOString(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'email_verified_at' => $this->emailVerifiedAt,
            'two_factor_enabled' => $this->twoFactorEnabled,
            'profile' => $this->profile?->toArray(),
            'created_at' => $this->createdAt,
            'updated_at' => $this->updateAt,
        ];
    }
}
