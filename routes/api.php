<?php

use App\Exports\FijoExport;
use App\Exports\MovilExport;
use App\Http\Controllers\Api\CelularesController;
use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\PlanesController;
use App\Http\Controllers\Api\SedeController;
use App\Http\Controllers\Api\SedeVendedorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Api\FijoController;
use App\Http\Controllers\Api\MovilController;

Route::post('/v1/login', [App\Http\Controllers\api\AuthController::class, 'login'])->name('api.login');
Route::post('/v1/register', [App\Http\Controllers\api\AuthController::class, 'register'])->name('api.register');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/v1/logout', [App\Http\Controllers\api\AuthController::class, 'logout'])->name('api.logout');
    Route::apiResource('celulares', CelularesController::class);
    Route::apiResource('clientes', ClientesController::class);
    Route::apiResource('planes', PlanesController::class);
    Route::apiResource('sede', SedeController::class);
    Route::apiResource('fijo', FijoController::class);
    Route::apiResource('movil', MovilController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::middleware(['auth:sanctum', 'role:asesor'])->group(function () {
    Route::post('/v1/mercadopago/preference', [App\Http\Controllers\MercadoPagoController::class, 'createPreference'])->name('api.mercadopago.preference');
});

Route::middleware(['auth:sanctum', 'role:super'])->group(function () {
    Route::apiResource('sede-vendedores', SedeVendedorController::class);
    Route::get('/export-movil', function () {
        return Excel::download(new MovilExport, 'movil.xlsx');
    });
    Route::get('export-fijo', function () {
        return Excel::download(new FijoExport, 'fijo.xlsx');
    });
});
