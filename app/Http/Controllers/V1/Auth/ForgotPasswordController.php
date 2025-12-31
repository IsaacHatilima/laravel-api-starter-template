<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\SendResetPasswordLinkAction;
use App\DTOs\V1\Command\Auth\ForgotPasswordRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ForgotPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function __construct(
        private readonly SendResetPasswordLinkAction $action,
    ) {
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $dto = ForgotPasswordRequestDTO::fromRequest($request);

        $this->action->execute($dto);

        return $this->success(message: 'Reset password link sent successfully');
    }
}
