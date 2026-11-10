<?php

use App\Http\Controllers\CourierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Pago Courier
Route::get('/courier', [CourierController::class, 'index'])->name('courier.index');
Route::post('/courier', [CourierController::class, 'subir'])->name('courier.subir');
Route::get('/courier/{id}', [CourierController::class, 'ver'])->whereUuid('id')->name('courier.ver');
Route::get('/courier/{id}/estado', [CourierController::class, 'estado'])->whereUuid('id')->name('courier.estado');
