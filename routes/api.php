<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () 
{
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::prefix('rifas')->name('rifa.')->group(function () {
        
    });

    Route::prefix('orders')->name('order.')->group(function () {

    });

    Route::prefix('configuration')->name('configuration.')->group(function () {

    });

    Route::prefix('clients')->name('clientes.')->group(function () {

    });

});
