<?php

namespace App\Exceptions;

use Exception;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class ErrorHttpException extends RuntimeException implements ErrorHttpExceptionInterface
{
    protected $statusCode;
    protected $headers;
    protected $errors;

    public function __construct(
        int $code = 0,
        string $message = null,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        Exception $previous = null,
        array $headers = [],
        array $errors = null
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->errors = $errors;

        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function setErrors(?array $errors)
    {
        $this->errors = $errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
