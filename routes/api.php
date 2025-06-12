<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RifaController;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('public')->group(function () {
    Route::prefix('rifas')->name('rifa.')->group(function () {
        Route::get('/active', [RifaController::class, 'getRifasActive']);
        Route::get('/byId/{id}', [RifaController::class, 'getRifaById']);
    });
});
Route::middleware('auth:sanctum')->group(function () 
{
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::prefix('rifas')->name('rifa.')->group(function () {
        Route::get('/', [RifaController::class, 'getRifas']);
        Route::post('/', [RifaController::class, 'createRifa']);
        Route::post('/u/{id}', [RifaController::class, 'updateRifa']);
        Route::post('/status/u/{id}', [RifaController::class, 'updateStatusRifa']);
        Route::post('/rewards/u/{id}', [RifaController::class, 'updateRewards']);
    });

    Route::prefix('orders')->name('order.')->group(function () {

    });

    Route::prefix('configuration')->name('configuration.')->group(function () {

    });

    Route::prefix('clients')->name('clientes.')->group(function () {

    });

});
