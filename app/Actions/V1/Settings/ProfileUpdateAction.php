<?php

namespace App\Actions\V1\Settings;

use App\DTOs\V1\Command\Settings\ProfileUpdateRequestDTO;
use App\Jobs\SendVerificationEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class ProfileUpdateAction
{
    /**
     * @throws Throwable
     */
    public function execute(ProfileUpdateRequestDTO $dto, User $user): User
    {
        return DB::transaction(function () use ($dto, $user) {
            $user->profile->update([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
            ]);

            if ($user->email !== $dto->email) {
                $user->email = $dto->email;
                $user->email_verified_at = null;
                $user->save();

                SendVerificationEmailJob::dispatch($user)->afterCommit();
            }

            return $user->load('profile');
        });
    }
}
