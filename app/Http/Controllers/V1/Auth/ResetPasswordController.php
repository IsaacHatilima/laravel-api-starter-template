<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\ResetPasswordAction;
use App\Actions\V1\Auth\VerifyResetPasswordAction;
use App\DTOs\V1\Command\Auth\ResetPasswordDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\ResetPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function __construct(
        private readonly ResetPasswordAction $resetPasswordAction,
        private readonly VerifyResetPasswordAction $verifyAction
    ) {
    }

    public function verify(Request $request): JsonResponse
    {
        $this->verifyAction->execute($request->query('id'));

        return response()->json([
            'message' => 'Token verified successfully',
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function change(ResetPasswordRequest $request): JsonResponse
    {
        $user = $this->verifyAction->execute($request->query('id'));

        $dto = ResetPasswordDTO::fromRequest($request);
        $message = $this->resetPasswordAction->execute($dto, $user);

        return response()->json([
            'message' => $message,
        ]);
    }
}
