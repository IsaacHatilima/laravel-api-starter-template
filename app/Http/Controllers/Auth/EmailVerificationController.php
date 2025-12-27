<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\EmailVerificationAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function __invoke(Request $request, EmailVerificationAction $action): JsonResponse
    {
        $id = $request->query('id');

        $response = $action->execute($id);

        return response()->json($response);
    }
}
