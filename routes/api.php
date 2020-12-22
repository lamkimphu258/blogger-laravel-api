<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TopicController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);

    Route::prefix('topics')->group(function () {
        Route::post('/', [TopicController::class, 'store'])->middleware('auth:api');
        Route::get('/', [TopicController::class, 'index']);
        Route::get('/{topicId}', [TopicController::class, 'show']);

        Route::prefix('{topicId}/posts')->group(function () {
            Route::post('/', [PostController::class, 'store'])->middleware('auth:api');
        });
    });
});
