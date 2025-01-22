<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GajiController;

Route::get('/', [GajiController::class, 'index'])->name('gaji.index');
Route::post('/gaji', [GajiController::class, 'store'])->name('gaji.store');
Route::delete('/gaji/{id}', [GajiController::class, 'destroy'])->name('gaji.destroy');
Route::get('/cetak/{id}', [GajiController::class, 'cetak'])->name('gaji.cetak');
