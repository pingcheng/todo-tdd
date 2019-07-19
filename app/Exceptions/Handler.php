<?php

namespace App\Exceptions;

use App\Exceptions\ApiExceptions\ApiException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

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
                return response()->json([
                    'code' => 422,
                    'data' => $exception->errors()
                ], 422);

            case ApiException::class:
                return response()->json([
                    'code' => $exception->getCode(),
                    'data' => $exception->getMessage(),
                ], $exception->getCode());

            case AuthenticationException::class:
                return response()->json([
                    'code' => 401,
                    'data' => 'unauthenticated'
                ], 401);

            case ModelNotFoundException::class:
                return response()->json([
                   'data' => 'model is not found',
                   'code' => 404,
                ], 404);

            default:
                return response()->json([
                'data' => 'unknown error',
                'code' => 500,
            ], 500);
        }
    }
}
