<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserCredentialRequest;
use App\Http\Requests\RegisterUserCredentialRequest;
use App\Http\Response\ApiErrorCode;
use App\Http\Response\ApiErrorResponse;
use App\Models\UserCredential;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserAuthorizationController extends Controller
{
    public function register(RegisterUserCredentialRequest $request): Response
    {
        $hashedUserCredential = UserCredential::hashPassword($request);
        $hashedUserCredential->save();

        return response(null, HttpResponse::HTTP_CREATED);
    }

    public function login(LoginUserCredentialRequest $request): JsonResponse|ApiErrorResponse
    {
        if ($token = UserCredential::verifyCredentialAndIssueToken($request)) {
            return response()->json([
                'token' => $token->token
            ])->setStatusCode(HttpResponse::HTTP_OK);
        }

        return new ApiErrorResponse(ApiErrorCode::AuthFailed, 'Invalid credentials.');
    }

    public function show_me(Request $request): JsonResponse
    {
        return response()->json([
            'credential' => $request->attributes->get('auth.credential')
        ])->setStatusCode(HttpResponse::HTTP_OK);
    }
}
