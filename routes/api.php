<?php

use App\Http\Controllers\UserAuthorizationController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('auth')->group(function () {
    Route::middleware('auth.token')->group(function () {
        Route::post('/login', [UserAuthorizationController::class, 'login']);
        Route::post('/register', [UserAuthorizationController::class, 'register']);
    });

    Route::middleware('auth.token:required')->get('/me', [UserAuthorizationController::class, 'show_me']);
});

Route::prefix('users')->group(function () {
    Route::middleware('auth.token')->group(function () {
        Route::get('/{user}', [UsersController::class, 'show']);
    });

    Route::middleware('auth.token:required')->group(function () {
        Route::post('', [UsersController::class, 'create']);
        Route::get('/me', [UsersController::class, 'show_me']);
        Route::patch('/me', [UsersController::class, 'update_me']);
    });
});
