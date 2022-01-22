<?php

namespace App\Http\Response;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use \Symfony\Component\HttpFoundation\Response as HttpResponse;

class ApiErrorResponse implements Responsable
{
    private ApiErrorCode $code;
    private ?string $message;

    public function __construct(ApiErrorCode $code, string $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public function toResponse($request): \Illuminate\Http\JsonResponse
    {
        $statusCode = 500;
        $response = new JsonResponse();

        if($this->code === ApiErrorCode::InvalidRequest) {
            $statusCode = HttpResponse::HTTP_BAD_REQUEST;
        }
        if($this->code === ApiErrorCode::NotFound) {
            $statusCode = HttpResponse::HTTP_NOT_FOUND;
        }
        if($this->code === ApiErrorCode::NotAllowed) {
            $statusCode = HttpResponse::HTTP_METHOD_NOT_ALLOWED;
        }
        if($this->code === ApiErrorCode::AuthFailed) {
            $statusCode = HttpResponse::HTTP_UNAUTHORIZED;
            $response->header('WWW-Authenticate', 'Bearer');
        }
        if($this->code === ApiErrorCode::InvalidToken) {
            $statusCode = HttpResponse::HTTP_UNAUTHORIZED;
            $response->header('WWW-Authenticate', 'Bearer error="invalid_token"');
        }

        return $response->setData([
            'error' => [
                'code' => $statusCode,
                'message' => $this->message
            ]
        ])->setStatusCode($statusCode);
    }
}

enum ApiErrorCode {
    case InvalidRequest;
    case NotFound;
    case NotAllowed;
    case AuthFailed;
    case InvalidToken;
    case ServerError;
}
