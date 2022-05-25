<?php

namespace App\Exceptions;

use App\Traits\ErrorResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ErrorResponse;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        ExampleErrorException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $e
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return Response|JsonResponse
     *
     */
    public function render($request, Throwable $e)
    {
        if (method_exists($e, 'render')) {
            return $e->render($request);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        } elseif ($e instanceof HttpResponseException) {
            return $e->getResponse();
        }

        if ($e instanceof ValidationException) {
            return $this->errorResponse($this->errorCode(4220), $e->getMessage(), $e->errors(), SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        } elseif ($e instanceof ModelNotFoundException) {
            return $this->errorResponse($this->errorCode(4041), $e->getMessage() ?: 'Data not found', null, SymfonyResponse::HTTP_NOT_FOUND);
        } elseif ($e instanceof NotFoundHttpException) {
            return $this->errorResponse($this->errorCode(4040), 'Page not found', null, SymfonyResponse::HTTP_NOT_FOUND, $e->getHeaders());
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse($this->errorCode(4050), 'Method not allowed', null, SymfonyResponse::HTTP_METHOD_NOT_ALLOWED, $e->getHeaders());
        } elseif ($e instanceof ErrorHttpExceptionInterface) {
            return $this->errorResponse($e->getCode(), $e->getMessage(), $e->getErrors(), $e->getStatusCode(), $e->getHeaders());
        } elseif ($e instanceof HttpExceptionInterface) {
            return $this->errorResponse($this->httpToErrorCode($e), $e->getMessage(), null, $e->getStatusCode(), $e->getHeaders());
        }

        return $this->errorResponse($e->getCode());
    }

}
