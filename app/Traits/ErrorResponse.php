<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;


trait ErrorResponse
{
    public function errorCode(int $code): int
    {
        return config('app.error_code_prefix') + $code;
    }

    public function errorResponse(
        int $code = 0,
        string $message = 'Internal server error.',
        $errors = null,
        $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        array $headers = []
    ): JsonResponse {
        return new JsonResponse([
            'code' => $code,
            'message' => $message,
            'errors' => $errors,
        ], $status, $headers);
    }

    public function httpToErrorCode(HttpExceptionInterface $e): int
    {
        return $e->getCode() ?: error_code($e->getStatusCode() * 10);
    }

}
