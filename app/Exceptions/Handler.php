<?php

namespace App\Exceptions;

use App\Exceptions\ApiExceptions\ApiException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use PingCheng\ApiResponse\ApiResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($request->expectsJson()) {
            return $this->commonJsonExceptionHandler($request, $exception);
        }

        return parent::render($request, $exception);
    }

    protected function commonJsonExceptionHandler($request, Exception $exception) {
        switch (get_class($exception)) {

            case ValidationException::class:
                /** @var $exception ValidationException */
                return ApiResponse::message(422, $exception->errors());

            case AuthenticationException::class:
                return ApiResponse::unauthorized('unauthenticated');

            case ModelNotFoundException::class:
                return ApiResponse::notFound('model is not found');

            case MethodNotAllowedHttpException::class:
                return ApiResponse::notFound('api endpoint is not define');
        }


        if (is_a($exception, ApiException::class)) {
            return ApiResponse::message($exception->getCode(), $exception->getMessage());
        }

        return ApiResponse::internalServerError('unknown error');
    }
}
