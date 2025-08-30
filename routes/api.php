<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockController;
use Illuminate\Routing\RouteRegistrar;
use App\Http\Controllers\StockUserController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('stocks', StockController::class);

    Route::apiResource('users', UserController::class);

    Route::post('/user/stock/attach', [StockUserController::class, 'attachStock']);
    Route::post('/user/stock/detach', [StockUserController::class, 'detachStock']);
    Route::get('/stock-users', [StockUserController::class, 'index']);
    Route::put('/user/stock/update', [StockUserController::class, 'updateStockQuantity']);
});
