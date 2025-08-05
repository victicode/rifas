<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RifaController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\WinnerController;
use App\Http\Controllers\Api\PayMethodController;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('public')->group(function () {
    Route::prefix('rifas')->name('rifa.')->group(function () {
        Route::get('/active', [RifaController::class, 'getRifasActive']);
        Route::get('/stadistics', [RifaController::class, 'getRifaDataStadistics']);

        Route::get('/byId/{id}', [RifaController::class, 'getRifaById']);
    });
    Route::prefix('order')->name('order.')->group(function () {
        Route::post('/', [OrderController::class, 'createOrder']);
        Route::get('/byId/{id}', [OrderController::class, 'getOrderById']);
        Route::get('/byCi/{ci}', [OrderController::class, 'findOrdersByCiClient']);        
        Route::get('/byId/html/{id}', [OrderController::class, 'getOrderByIdHtml']);
    });

    Route::prefix('winners')->name('winner.')->group(function () {
        Route::get('/', [WinnerController::class, 'getWinnersPagination']);
    });
    
    Route::prefix('method_pays')->name('methods')->group(function () {
        Route::get('/', [PayMethodController::class, 'getMethodsActive']);
    });
});


Route::middleware('auth:sanctum')->group(function () 
{
    Route::get('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::prefix('rifas')->name('rifa.')->group(function () {
        Route::get('/', [RifaController::class, 'getRifas']);
        Route::get('/all', [RifaController::class, 'getAllRifas']);
        Route::get('/tickets/{id}', [RifaController::class, 'getTicketsInRifa']);
        Route::get('/with_report/{id}', [RifaController::class, 'getRifaWithReport']);
        Route::post('/', [RifaController::class, 'createRifa']);
        Route::post('/u/{id}', [RifaController::class, 'updateRifa']);
        Route::post('/d/{id}', [RifaController::class, 'deleteRifa']);
        Route::post('/status/u/{id}', [RifaController::class, 'updateStatusRifa']);
        Route::post('/rewards/u/{id}', [RifaController::class, 'updateRewards']);
    });

    Route::prefix('orders')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'getOrderPagination']);
        Route::post('/d/{id}', [OrderController::class, 'deleteOrder']);
        Route::post('/changeStatus/{id}', [OrderController::class, 'changeStatus']);
    });
    Route::prefix('clients')->name('client.')->group(function () {
        Route::get('/', [ClientController::class, 'getClientPagination']);
        Route::post('/d/{id}', [ClientController::class, 'deleteClient']);
        Route::post('/changeStatus/{id}', [ClientController::class, 'changeStatus']);
    });
    Route::prefix('methods_pay')->name('methods')->group(function () {
        Route::get('/', [PayMethodController::class, 'getMethodsData']);
        Route::get('/methods', [PayMethodController::class, 'getPayMethods']);
        Route::post('/methods/u/{id}', [PayMethodController::class, 'updatePayMethod']);
        Route::post('/data', [PayMethodController::class, 'createMethodData']);
        Route::post('/data/u/{id}', [PayMethodController::class, 'updateMethodData']);
        Route::post('/data/d/{id}', [PayMethodController::class, 'deleteMethodData']);

    });

    Route::prefix('winners')->name('winners.')->group(function () {
        Route::get('/', [WinnerController::class, 'getWinnersPagination']);
        Route::post('/', [WinnerController::class, 'storeWinner']);
        Route::get('/methods', [WinnerController::class, 'getPayMethods']);


    });
    

    Route::prefix('configuration')->name('configuration.')->group(function () {

    });

    Route::prefix('clients')->name('clientes.')->group(function () {

    });

});
