<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\SendResetPasswordLinkAction;
use App\DTOs\V1\Command\Auth\ForgotPasswordDTO;
use App\Enums\ActionStatusEnum;
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
        $dto = ForgotPasswordDTO::fromRequest($request);
        $result = $this->action->execute($dto);

        if ($result === ActionStatusEnum::FAILED) {
            return $this->fail(
                message: 'Failed to send reset password link'
            );
        }

        return $this->success(message: 'Reset password link sent successfully');
    }
}
