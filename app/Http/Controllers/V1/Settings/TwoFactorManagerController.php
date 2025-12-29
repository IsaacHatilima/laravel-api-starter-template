<?php

namespace App\Http\Controllers\V1\Settings;

use App\Actions\V1\Settings\ConfirmTwoFactorAction;
use App\Actions\V1\Settings\DisableTwoFactorAction;
use App\Actions\V1\Settings\EnableTwoFactorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\TwoFactorCodeRequest;
use App\Traits\InteractsWithAuth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use JsonException;
use RuntimeException;
use Throwable;

class TwoFactorManagerController extends Controller
{
    use InteractsWithAuth;

    public function __construct(
        private readonly EnableTwoFactorAction $enableTwoFactorAction,
        private readonly ConfirmTwoFactorAction $confirmTwoFactorAction,
        private readonly DisableTwoFactorAction $disableTwoFactorAction,
    ) {
    }

    /**
     * @throws JsonException
     * @throws AuthenticationException
     */
    public function enable(): JsonResponse
    {
        $user = $this->user();

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

    /**
     * @throws AuthenticationException
     */
    public function confirm(TwoFactorCodeRequest $request): JsonResponse
    {
        $user = $this->user();

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
    public function disable(): JsonResponse
    {
        $user = $this->user();

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
