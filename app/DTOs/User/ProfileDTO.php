<?php

namespace App\DTOs\User;

use App\DTOs\BaseDTO;
use App\Models\Profile;

/**
 * @extends BaseDTO<string, mixed>
 */
readonly class ProfileDTO extends BaseDTO
{
    public function __construct(
        public string $id,
        public string $firstName,
        public string $lastName,
        public ?string $createdAt,
        public ?string $updateAt,
    ) {
    }

    public static function fromModel(Profile $profile): self
    {
        return new self(
            id: $profile->public_id,
            firstName: $profile->first_name,
            lastName: $profile->last_name,
            createdAt: $profile->created_at?->toISOString(),
            updateAt: $profile->updated_at?->toISOString(),
        );
    }

    /**
     * Convert the DTO to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updateAt,
        ];
    }
}
