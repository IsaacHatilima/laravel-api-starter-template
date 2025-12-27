<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\LogoutUserAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    public function __construct(private readonly LogoutUserAction $logoutAction)
    {
    }

    public function __invoke(): JsonResponse
    {
        $this->logoutAction->execute();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
