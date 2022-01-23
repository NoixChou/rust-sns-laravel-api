<?php

namespace App\Exceptions;

use App\Http\Response\ApiErrorCode;
use App\Http\Response\ApiErrorResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return new ApiErrorResponse(ApiErrorCode::InvalidRequest, 'Failed to parse request.');
        }

        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();

            if ($statusCode === 404) {
                return new ApiErrorResponse(ApiErrorCode::NotFound, 'No endpoint found.');
            }

            if ($statusCode < 500) {
                return new ApiErrorResponse(ApiErrorCode::InvalidRequest, 'Invalid request.');
            }

            return new ApiErrorResponse(ApiErrorCode::ServerError, 'Something went wrong.');
        }

        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
