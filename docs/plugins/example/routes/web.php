<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('plugin-loyalty-rewards::dashboard');
})->name('dashboard');
