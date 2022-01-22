<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Response\ApiErrorCode;
use App\Http\Response\ApiErrorResponse;
use App\Models\User;
use App\Models\UserCredential;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function create(CreateUserRequest $request)
    {
        $authorizedUser = $request->attributes->get('auth.credential');
        if (!($authorizedUser instanceof UserCredential)) {
            return new ApiErrorResponse(ApiErrorCode::ServerError);
        }

        if ($request->attributes->get('auth.user')) {
            return new ApiErrorResponse(ApiErrorCode::InvalidRequest, 'User already created.');
        }

        $newUser = new User();
        $newUser->fill($request->validated());
        $newUser->id = $authorizedUser->id;
        $newUser->website ??= '';
        $newUser->save();

        return response()->json([
            'user' => [
                'id' => $newUser->id
            ]
        ]);
    }

    public function show(User $user)
    {
        //
    }

    public function show_me(User $user)
    {
        //
    }

    public function update_me(Request $request)
    {
        //
    }
}
