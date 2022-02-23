<?php

namespace App\Exceptions;

use App\Services\ApiResponse;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\MockObject\Api;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

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
     *
     * @throws \Exception
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
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $e)
    {
        //validate
        if ($e instanceof ValidationException) {
            $errors = array_values($e->errors());
            $msg = array_shift($errors);
            return $this->fail($msg[0]);
        }
        // Unauthorized
        if ($e instanceof UnauthorizedHttpException || $e instanceof AuthenticationException || $e instanceof  TokenBlacklistedException) {
            return $this->fail('Unauthorized!', 401, 401);
        }

        return parent::render($request, $e);
    }
}
