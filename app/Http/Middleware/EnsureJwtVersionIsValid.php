<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EnsureJwtVersionIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     *
     * @throws UnauthorizedHttpException|JWTException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (! $user instanceof User) {
            return response()->json([
                'error' => 'Unauthenticated',
            ], 401);
        }

        $payload = JWTAuth::parseToken()->getPayload();
        $rawVersion = $payload->get('ver');

        if (! is_int($rawVersion)) {
            return response()->json([
                'error' => 'Invalid token',
            ], 401);
        }

        if ($rawVersion !== $user->token_version) {
            return response()->json([
                'error' => 'Unauthenticated',
            ], 401);
        }

        return $next($request);
    }
}
