<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::apiResource('books', BookController::class);
        Route::post('/rentals', [RentalController::class, 'store']);
        Route::post('/rentals/{rental}/return', [RentalController::class, 'returnBook']);
        Route::get('/my-rentals', [RentalController::class, 'myRentals']);
    });
});
