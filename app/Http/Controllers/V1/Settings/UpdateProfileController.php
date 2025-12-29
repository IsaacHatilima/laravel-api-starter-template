<?php

namespace App\Http\Controllers\V1\Settings;

use App\Actions\V1\Settings\ProfileUpdateAction;
use App\DTOs\V1\Command\Settings\ProfileUpdateRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Settings\ProfileUpdateRequest;
use App\Traits\InteractsWithAuth;
use Illuminate\Http\JsonResponse;
use Throwable;

class UpdateProfileController extends Controller
{
    use InteractsWithAuth;

    public function __construct(private readonly ProfileUpdateAction $action)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $this->user();

        $dto = ProfileUpdateRequestDTO::fromRequest($request);

        $this->action->execute($dto, $user);

        return response()->json(['message', 'Profile updated successfully']);
    }
}
