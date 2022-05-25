<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ErrorException extends \RuntimeException implements ErrorHttpExceptionInterface
{
    protected $statusCode;
    protected $headers;
    protected $errors;

    /**
     * ErrorException constructor.
     *
     * @param int $code
     * @param int $statusCode
     * @param string|null $message
     * @param Exception|null $previous
     * @param array $headers
     * @param array|null $errors
     */
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

        if ($message === null || $message == '') {
            $message = error_msg($code);
        }

        parent::__construct($message, config('app.error_code_prefix') + $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
