<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\DTOs\Command\Auth\RegisterDTO;
use App\DTOs\Read\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class RegisterController extends Controller
{
    public function __construct(
        private readonly RegisterUserAction $registerAction,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $dto = RegisterDTO::fromRequest($request);
        $user = $this->registerAction->execute($dto);

        return response()->json(UserDTO::fromModel($user), 201);
    }
}
