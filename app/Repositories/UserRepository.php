<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final readonly class UserRepository
{
    /**
     * @param array<string, mixed> $criteria
     */
    public function findOne(array $criteria): ?User
    {
        return User::query()
            ->where($criteria)
            ->first();
    }

    /**
     * @param array<string, mixed> $criteria
     *
     * @return Collection<int, User>
     */
    public function findMultiple(array $criteria): Collection
    {
        return User::query()
            ->where($criteria)
            ->get();
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

        return $user;
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }
}
