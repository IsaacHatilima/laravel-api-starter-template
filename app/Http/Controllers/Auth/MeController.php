<?php

namespace App\Http\Controllers\Auth;

use App\DTOs\Read\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class MeController extends Controller
{
    public function __invoke(): JsonResponse
    {
        /** @var User|null $user */
        $user = auth('api')->user();

        if (! $user) {
            throw new UnauthorizedHttpException('Bearer');
        }

        $user->load('profile');

        return response()->json(
            UserDTO::fromModel($user)
        );
    }
}
