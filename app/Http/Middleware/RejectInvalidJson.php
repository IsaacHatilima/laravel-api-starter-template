<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RejectInvalidJson
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->isJson()
            && in_array($request->method(), ['POST', 'PUT', 'PATCH'])
            && $request->getContent() !== ''
        ) {
            $decoded = json_decode($request->getContent(), true);

            if (! is_array($decoded)) {
                return response()->json([
                    'message' => 'JSON payload must be an object',
                ], 400);
            }
        }

        return $next($request);
    }
}
