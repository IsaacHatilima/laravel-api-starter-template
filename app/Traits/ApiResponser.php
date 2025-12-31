<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponser
{
    /**
     * Core response orchestrator.
     *
     * @param array<string, mixed>|null $meta
     * @param array<string, mixed>|null $errors
     */
    protected function respond(
        bool $success,
        string $message,
        mixed $data = null,
        ?array $meta = null,
        ?array $errors = null,
        int $code = Response::HTTP_OK
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
     * 200 OK - Standard success response.
     *
     * @param array<string, mixed>|null $meta
     */
    protected function success(mixed $data = null, string $message = 'Success', ?array $meta = null): JsonResponse
    {
        return $this->respond(true, $message, $data, $meta);
    }

    /**
     * 201 Created - Resource successfully created.
     */
    protected function created(mixed $data, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->respond(true, $message, $data, null, null, Response::HTTP_CREATED);
    }

    /**
     * 204 Deleted - Resource successfully deleted.
     */
    protected function deleted(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * 400 Bad Request - General client-side error.
     *
     * @param array<string, mixed>|null $errors
     */
    protected function fail(
        string $message = 'Bad request',
        int $code = Response::HTTP_BAD_REQUEST,
        ?array $errors = null
    ): JsonResponse {
        return $this->respond(false, $message, null, null, $errors, $code);
    }
}
