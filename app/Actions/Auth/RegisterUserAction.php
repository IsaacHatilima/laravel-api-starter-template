<?php

namespace App\Actions\Auth;

use App\DTOs\Command\Auth\RegisterDTO;
use App\Jobs\SendVerificationEmailJob;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

final readonly class RegisterUserAction
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws Throwable
     */
    public function execute(RegisterDTO $dto): User
    {
        return DB::transaction(function () use ($dto): User {
            /** @var User $user */
            $user = $this->userRepository->create([
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
            ]);

            $user->profile()->create([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
            ]);

            $user->load('profile');

            SendVerificationEmailJob::dispatch($user)->afterCommit();

            return $user;
        });
    }
}
