<?php

namespace App\Actions\V1\Auth;

use App\DTOs\V1\Command\Auth\RegisterRequestDTO;
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
    public function execute(RegisterRequestDTO $dto): User
    {
        $user = DB::transaction(function () use ($dto): User {
            $user = $this->userRepository->create([
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
            ]);

            $user->profile()->create([
                'first_name' => $dto->firstName,
                'last_name' => $dto->lastName,
            ]);

            return $user->load('profile');
        });

        SendVerificationEmailJob::dispatch($user);

        return $user;
    }
}
