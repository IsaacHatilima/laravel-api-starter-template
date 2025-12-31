<?php

namespace App\Http\Controllers\V1\Settings;

use App\Actions\V1\Settings\DeleteProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\CurrentPasswordRequest;
use App\Models\User;
use App\Traits\InteractsWithAuth;
use Illuminate\Http\JsonResponse;

class DeleteProfileController extends Controller
{
    use InteractsWithAuth;

    public function __construct(private readonly DeleteProfileAction $action)
    {
    }

    public function __invoke(CurrentPasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->action->execute($user);

        return $this->deleted('Profile deleted successfully');
    }
}
