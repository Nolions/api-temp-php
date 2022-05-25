<?php

namespace App\Exceptions;

use Exception;

class ExampleErrorException extends ErrorHttpException implements ErrorHttpExceptionInterface
{
    public function __construct(
        int $code = 0,
        string $message = null,
        int $statusCode = 499,
        Exception $previous = null,
        array $headers = [],
        array $errors = null
    ) {
        parent::__construct($code, $message, $statusCode, $previous, $headers, $errors);
    }
}
