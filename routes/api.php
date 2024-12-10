<?php

use App\Http\Controllers\Api\CelularesController;
use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\PlanesController;
use App\Http\Controllers\Api\SedeController;
use App\Http\Controllers\Api\SedeVendedorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/login', [App\Http\Controllers\api\AuthController::class, 'login'])->name('api.login');

Route::post('/v1/register', [App\Http\Controllers\api\AuthController::class, 'register'])->name('api.register');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/v1/logout', [App\Http\Controllers\api\AuthController::class, 'logout'])->name('api.logout');
    Route::apiResource('sede-vendedores', SedeVendedorController::class);
    Route::apiResource('celulares', CelularesController::class);
    Route::apiResource('clientes', ClientesController::class);
    Route::apiResource('planes', PlanesController::class);
    Route::apiResource('sede', SedeController::class);

    Route::post('/v1/mercadopago/preference', [App\Http\Controllers\MercadoPagoController::class, 'createPreference'])->name('api.mercadopago.preference');
});
