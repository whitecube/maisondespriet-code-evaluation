<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

Route::view('login', 'login')->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::get('/', [HomeController::class, 'show'])->name('home');
    Route::post('/order', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order/{order}', [OrderController::class, 'update'])->name('order.update');
});
