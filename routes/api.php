<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('throttle:5,1')->post('/send-otp', [\App\Http\Controllers\Api\Auth\LoginController::class, 'sendOtp']);
Route::post('/verify-otp', [\App\Http\Controllers\Api\Auth\LoginController::class, 'verify']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('v1')->group(function () {
      Route::prefix('users')->group(function () {
          Route::post('/update', [\App\Http\Controllers\Api\v1\UserController::class, 'update']);
      });

      Route::prefix('messages')->group(function () {
          Route::post('/', [\App\Http\Controllers\Api\v1\Messages\MessageController::class, 'store']);
          Route::get('/show', [\App\Http\Controllers\Api\v1\Messages\MessageController::class, 'show']);
          Route::get('/user/messages', [\App\Http\Controllers\Api\v1\Messages\MessageController::class, 'getMessagesForUser']);

          Route::post('/typing', [\App\Http\Controllers\Api\v1\Messages\MessageController::class, 'updateTypingStatus']);

          Route::post('/seen', [\App\Http\Controllers\Api\v1\Messages\MessageController::class, 'updateSeenStatus']);

          Route::prefix('forwarded')->group(function () {
              Route::post('/', [\App\Http\Controllers\Api\v1\Messages\ForwardedMessageController::class, 'store']);
              Route::get('/show', [\App\Http\Controllers\Api\v1\Messages\ForwardedMessageController::class, 'show']);
          });

          Route::prefix('thread')->group(function () {
              Route::post('/', [\App\Http\Controllers\Api\v1\Messages\ReplyThreadController::class, 'store']);
              Route::get('/show', [\App\Http\Controllers\Api\v1\Messages\ReplyThreadController::class, 'show']);
          });
      });

      Route::prefix('broadcast')->group(function () {
          Route::post('/', [\App\Http\Controllers\Api\v1\Broadcasr\BroadcastController::class, 'sendBroadcast']);
      });
    });
});
