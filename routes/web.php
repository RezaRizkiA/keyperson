<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('client');
});

Route::get('/expert', function () {
    return view('expert');
})->name('expert');
