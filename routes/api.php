<?php

use App\Exports\FijoAsesorExport;
use App\Exports\FijoExport;
use App\Exports\MovilAsesorExport;
use App\Exports\MovilExport;
use App\Http\Controllers\Api\CelularesController;
use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\FijoController;
use App\Http\Controllers\Api\MovilController;
use App\Http\Controllers\Api\PlanesController;
use App\Http\Controllers\Api\SedesController;
use App\Http\Controllers\Api\SedeVendedorController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\MetaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::post('login', [App\Http\Controllers\api\AuthController::class, 'login'])->name('api.login');
Route::post('register', [App\Http\Controllers\api\AuthController::class, 'register'])->name('api.register');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [App\Http\Controllers\api\AuthController::class, 'logout'])->name('api.logout');
    Route::apiResource('celulares', CelularesController::class);
    Route::apiResource('clientes', ClientesController::class);
    Route::apiResource('planes', PlanesController::class);
    Route::apiResource('sedes', SedesController::class);
    Route::apiResource('fijo', FijoController::class);
    Route::apiResource('movil', MovilController::class);
    Route::post('mercadopago/preference', [App\Http\Controllers\MercadoPagoController::class, 'createPreference'])->name('api.mercadopago.preference');
    Route::get('usuario', function (Request $request) {
        return $request->user();
    });
    Route::get('usuarios/{id}/export/movil', function ($asesor) {
        return Excel::download(new MovilAsesorExport($asesor), 'movilAsesor.xlsx');
    });
    Route::get('usuarios/{id}/export/fijo', function ($asesor) {
        return Excel::download(new FijoAsesorExport($asesor), 'fijoAsesor.xlsx');
    });
    Route::apiResource('facturas', FacturaController::class);
    Route::post('facturas/{id}/pdf', [FacturaController::class, 'generarPDF']);
    Route::get('facturas/progreso/{tipo_venta}', [FacturaController::class, 'progresoVentas']);
    Route::put('facturas/{id}/estado/{nuevoEstado}', [FacturaController::class, 'editarEstado']);
});

Route::middleware(['auth:sanctum', 'role:coordinador'])->group(function () {
    Route::delete('fijo/{fijo}', [FijoController::class, 'destroy']);
    Route::delete('movil/{movil}', [MovilController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:super'])->group(function () {
    Route::apiResource('sede/vendedores', SedeVendedorController::class);
    Route::apiResource('usuarios', UserController::class);
    Route::apiResource('metas', MetaController::class);
    Route::get('/consolidado/movil', function () {
        return Excel::download(new MovilExport, 'movil.xlsx');
    });
    Route::get('/consolidado/fijo', function () {
        return Excel::download(new FijoExport, 'fijo.xlsx');
    });

    Route::delete('celulares/{celular}', [CelularesController::class, 'destroy']);
    Route::delete('clientes/{cliente}', [ClientesController::class, 'destroy']);
    Route::delete('planes/{plan}', [PlanesController::class, 'destroy']);
    Route::delete('sedes/{sede}', [SedesController::class, 'destroy']);
    Route::delete('fijo/{fijo}', [FijoController::class, 'destroy']);
    Route::delete('movil/{movil}', [MovilController::class, 'destroy']);
});
