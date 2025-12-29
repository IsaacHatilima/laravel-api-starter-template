<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponser
{
    /**
     * @param array<string, mixed>|null $meta
     * @param array<string, mixed>|null $errors
     */
    protected function respond(
        bool $success,
        string $message,
        mixed $data = null,
        ?array $meta = null,
        ?array $errors = null,
        int $code = 200
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Happy Path Helper
     *
     * @param array<string, mixed>|null $meta
     */
    protected function ok(mixed $data, string $message = 'Success', int $code = 200, ?array $meta = null): JsonResponse
    {
        return $this->respond(true, $message, $data, $meta, null, $code);
    }

    /**
     * Error Path Helper
     *
     * @param array<string, mixed>|null $errors
     */
    protected function fail(string $message, int $code = 400, ?array $errors = null): JsonResponse
    {
        return $this->respond(false, $message, null, null, $errors, $code);
    }
}
