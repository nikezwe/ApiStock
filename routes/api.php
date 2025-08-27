<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ici tu définis les routes API de ton application
|
*/


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('users', UserController::class);

    Route::apiResource('stocks', StockController::class);

    // Route::post('stocks/{id}/attach-user', [StockController::class, 'attachUser']);
    // Route::post('stocks/{id}/detach-user', [StockController::class, 'detachUser']);

    // Route::post('users/{id}/stocks', [UserController::class, 'addStock']);
    // Route::put('users/{userId}/stocks/{stockId}', [UserController::class, 'updateStock']);
    // Route::delete('users/{userId}/stocks/{stockId}', [UserController::class, 'deleteStock']);
});
