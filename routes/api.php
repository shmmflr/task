<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

//    Route::middleware('auth:api')->group(function () {
//        Route::get('/profile', function (Request $request) {
//            return $request->user();
//        });
//    });
});
