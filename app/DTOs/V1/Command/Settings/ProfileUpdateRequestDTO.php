<?php

namespace App\DTOs\V1\Command\Settings;

use App\Http\Requests\V1\Settings\ProfileUpdateRequest;
use Illuminate\Support\Str;

class ProfileUpdateRequestDTO
{
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName
    ) {
    }

    public static function fromRequest(ProfileUpdateRequest $request): self
    {
        return new self(
            email: trim(strtolower($request->string('email')->value())),
            firstName: trim(Str::title($request->string('first_name')->value())),
            lastName: trim(Str::title($request->string('last_name')->value())),
        );
    }
}
