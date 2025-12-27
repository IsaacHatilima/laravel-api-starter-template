<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendResetPasswordLinkAction;
use App\DTOs\Auth\ForgotPasswordDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
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
        $message = $this->action->execute($dto);

        return response()->json([
            'message' => $message,
        ]);
    }
}
