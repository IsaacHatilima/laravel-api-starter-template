<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\EndAllSessionsAction;
use App\Http\Controllers\Controller;
use App\Traits\InteractsWithAuth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class EndAllSessionsController extends Controller
{
    use InteractsWithAuth;

    public function __construct(
        private readonly EndAllSessionsAction $endAllSessionsAction,
    ) {
    }

    /**
     * @throws AuthenticationException
     */
    public function __invoke(): JsonResponse
    {
        $user = $this->user();

        $message = $this->endAllSessionsAction->execute($user);

        return response()->json([
            'message' => $message,
        ]);
    }
}
