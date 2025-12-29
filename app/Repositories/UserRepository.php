<?php

namespace App\Repositories;

use App\Models\User;

final readonly class UserRepository
{
    public function find(string $public_id): ?User
    {
        return User::query()
            ->with('profile')
            ->where('public_id', $public_id)
            ->first();
    }

    /**
     * @param array<string, mixed> $criteria
     */
    public function findBy(array $criteria): ?User
    {
        return User::query()
            ->with('profile')
            ->where($criteria)
            ->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()
            ->with('profile')
            ->where('email', $email)
            ->first();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        $user->load('profile');

        return $user;
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }
}
