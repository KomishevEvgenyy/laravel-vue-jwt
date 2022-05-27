<?php

use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\UserController;
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
Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);

Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('logout', [JWTAuthController::class, 'logout']);
    Route::post('refresh', [JWTAuthController::class, 'refresh']);
    Route::get('profile', [JWTAuthController::class, 'profile']);
    Route::get('user/list', [UserController::class, 'list']);
    Route::get('user/show', [UserController::class, 'show']);
    Route::post('user/update', [UserController::class, 'update']);
    Route::post('user/create', [UserController::class, 'create']);
});
