<?php

use App\Http\Controllers\PostsController;
use App\Http\Controllers\UserAuthorizationController;
use App\Http\Controllers\UsersController;
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
    Route::middleware('auth.token:required')->get('/me', [UserAuthorizationController::class, 'show_me']);

    Route::middleware('auth.token')->group(function () {
        Route::post('/login', [UserAuthorizationController::class, 'login']);
        Route::post('/register', [UserAuthorizationController::class, 'register']);
    });
});

Route::prefix('users')->group(function () {
    Route::middleware('auth.token:required')->group(function () {
        Route::post('', [UsersController::class, 'create']);
        Route::get('/me', [UsersController::class, 'show_me']);
        Route::patch('/me', [UsersController::class, 'update_me']);
        Route::get('/me/posts', [PostsController::class, 'my_index']);
        Route::get('/{user}/posts', [PostsController::class, 'users_index']);
    });

    Route::middleware('auth.token')->group(function () {
        Route::get('/{user}', [UsersController::class, 'show']);
    });
});

Route::prefix('posts')->middleware('auth.token:required')->group(function () {
    Route::post('', [PostsController::class, 'create']);
    Route::get('/{post}', [PostsController::class, 'show']);
    Route::delete('/{post}', [PostsController::class, 'delete']);
});
