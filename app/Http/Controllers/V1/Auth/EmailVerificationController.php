<?php

namespace App\Http\Controllers\V1\Auth;

use App\Actions\V1\Auth\EmailVerificationAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __invoke(Request $request, EmailVerificationAction $action): JsonResponse
    {
        $action->execute($request);

        return $this->success(null, 'Email verified successfully');
    }
}
