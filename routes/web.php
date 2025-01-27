<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GajiController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [GajiController::class, 'index'])->name('gaji.index');
    Route::post('/gaji', [GajiController::class, 'store'])->name('gaji.store');
    Route::delete('/gaji/{id}', [GajiController::class, 'destroy'])->name('gaji.destroy');
    Route::get('/cetak/{id}', [GajiController::class, 'cetak'])->name('gaji.cetak');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store'])->name('register.store');
