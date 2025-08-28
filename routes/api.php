<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('users/{userId}/stocks')->group(function () {
        Route::post('/', [UserController::class, 'addStock']);
        Route::put('/{stockId}', [UserController::class, 'updateStock']);
        Route::delete('/{stockId}', [UserController::class, 'deleteStock']);
    });
    

    Route::post('stocks/{id}/attach-user', [StockController::class, 'attachUser']);
    Route::delete('stocks/{id}/detach-user', [StockController::class, 'detachUser']);


    Route::apiResource('users', UserController::class);
    
    Route::apiResource('stocks', StockController::class);
});