<?php

use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return view('login');
})->name('login');

Route::get('/', function () {
    return view('home');
})->name('home')->middleware('auth');
