<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

interface ErrorHttpExceptionInterface extends HttpExceptionInterface
{
    public function getErrors();

    public function setErrors(?array $errors);

}
