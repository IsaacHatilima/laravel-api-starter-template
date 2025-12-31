<?php

namespace App\Http\Controllers\V1\Settings;

use App\Actions\V1\Settings\UpdatePasswordAction;
use App\DTOs\V1\Command\Settings\ChangePasswordRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Settings\ChangePasswordRequest;
use App\Traits\InteractsWithAuth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class UpdatePasswordController extends Controller
{
    use InteractsWithAuth;

    public function __construct(private readonly UpdatePasswordAction $action)
    {
    }

    /**
     * @throws AuthenticationException
     */
    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $user = $this->user();

        $dto = ChangePasswordRequestDTO::fromRequest($request);

        $this->action->execute($dto, $user);

        return $this->success(message: 'Password changed successfully');
    }
}
