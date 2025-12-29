<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\RegisterUserAction;
use App\DTOs\V1\Command\Auth\RegisterDTO;
use App\DTOs\V1\Read\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\RegisterRequest;
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

        return $this->ok(
            data: UserDTO::fromModel($user),
            message: 'User registered successfully',
            code: 201
        );
    }
}
