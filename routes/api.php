<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/v1/login', [App\Http\Controllers\api\AuthController::class, 'login'])->name('api.login');

Route::post('/v1/register', [App\Http\Controllers\api\AuthController::class, 'register'])->name('api.register');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/v1/logout', [App\Http\Controllers\api\AuthController::class, 'logout'])->name('api.logout');
});
