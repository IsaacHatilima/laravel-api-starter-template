<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\ConfirmTwoFactorAction;
use App\Actions\Auth\DisableTwoFactorAction;
use App\Actions\Auth\EnableTwoFactorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TwoFactorCodeRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use JsonException;
use RuntimeException;
use Throwable;

class TwoFactorManagerController extends Controller
{
    public function __construct(
        private readonly EnableTwoFactorAction $enableTwoFactorAction,
        private readonly ConfirmTwoFactorAction $confirmTwoFactorAction,
        private readonly DisableTwoFactorAction $disableTwoFactorAction,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function enable(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $result = $this->enableTwoFactorAction->execute($user);
        } catch (RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }

        return response()->json([
            'message' => '2FA enabled. Please scan QR code and confirm with a code',
            'qr_code' => $result['qr_code'],
            'recovery_codes' => $result['recovery_codes'],
        ]);
    }

    public function confirm(TwoFactorCodeRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $this->confirmTwoFactorAction->execute($user, $request->string('code')->value());
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Invalid verification code'], 422);
        }

        return response()->json(['message' => '2FA confirmed successfully']);
    }

    /**
     * @throws Throwable
     */
    public function disable(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $this->disableTwoFactorAction->execute($user);
        } catch (RuntimeException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 409);
        }

        return response()->json(['message' => '2FA disabled successfully']);
    }
}
