<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\LogoutUserAction;
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

        return $this->success(message: 'Successfully logged out');
    }
}
