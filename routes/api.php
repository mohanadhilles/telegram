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
          Route::put('/update', [\App\Http\Controllers\Api\v1\UserController::class, 'update']);
          Route::post('/status/typing', [\App\Http\Controllers\Api\v1\UserController::class, 'updateTypingStatus']);
      });
    });
});
