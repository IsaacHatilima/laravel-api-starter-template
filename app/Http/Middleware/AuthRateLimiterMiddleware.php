<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class AuthRateLimiterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->key($request);

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'error' => 'Too many requests. Please try again later.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        return $next($request);
    }

    private function key(Request $request): string
    {
        return implode('|', [
            'public-auth',
            $request->ip(),
            strtolower((string) $request->route()?->getName()),
        ]);
    }
}
