<?php

namespace App\Http\Controllers\V1\Auth;

use App\DTOs\V1\Read\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Traits\InteractsWithAuth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class MeController extends Controller
{
    use InteractsWithAuth;

    /**
     * @throws AuthenticationException
     */
    public function __invoke(): JsonResponse
    {
        $user = $this->user();

        $user->load('profile');

        return $this->success(
            data: UserDTO::fromModel($user)->toArray(),
            message: 'User retrieved successfully',
        );
    }
}
