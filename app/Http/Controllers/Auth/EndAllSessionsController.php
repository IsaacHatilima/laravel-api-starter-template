<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\EndAllSessionsAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class EndAllSessionsController extends Controller
{
    public function __construct(
        private readonly EndAllSessionsAction $endAllSessionsAction,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();

        $message = $this->endAllSessionsAction->execute($user);

        return response()->json([
            'message' => $message,
        ]);
    }
}
