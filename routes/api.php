<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use Illuminate\Routing\RouteRegistrar;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::apiResource('users', UserController::class);
    Route::apiResource('stocks', StockController::class);

    Route::post('users/{userId}/attach', [UserController::class, 'addStock']);

    Route::delete('users/{userId}/detach/{stockId}', [UserController::class, 'deleteStock']);

    Route::post('users/{userId}/new-stock', [UserController::class, 'createStock']);

    Route::apiResource('stock-user', StockController::class);

});
