<?php

namespace App\Http\Middleware;

use App\Http\Response\ApiErrorCode;
use App\Http\Response\ApiErrorResponse;
use App\Models\UserToken;
use Closure;
use Illuminate\Http\Request;

class TokenAuthenticateMiddleware
{
    public function handle(Request $request, Closure $next, $authRequired = null)
    {
        $request->attributes->remove('auth');
        $request->request->remove('auth');
        $request->query->remove('auth');

        $isAuthRequired = $authRequired === 'required';

        $inputToken = $request->bearerToken();
        if (!$isAuthRequired && !$inputToken) {
            return $next($request);
        }
        if (!$inputToken) {
            return new ApiErrorResponse(ApiErrorCode::AuthFailed, 'Authorization required.');
        }

        $storedToken = UserToken::fetchByTokenString($inputToken);

        if ($storedToken) {
            $request->attributes->add([
                'auth' => [
                    'credential' => $storedToken->userCredential,
                    'user' => $storedToken->userCredential->user
                ]
            ]);

            return $next($request);
        }

        return new ApiErrorResponse(ApiErrorCode::InvalidToken, 'Invalid token.');
    }
}
