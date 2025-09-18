<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/konyvek', function () {
    return view('konyvek');
});
