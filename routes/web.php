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
use App\Http\Controllers\BookController;

Route::get('/books', [BookController::class, 'index']);
Route::get('/books/search/{title}', [BookController::class, 'searchByTitle']);
Route::post('/create-book', [BookController::class, 'create']);
Route::put('/books/{id}', [BookController::class, 'update']);
Route::delete('/books/{id}', [BookController::class, 'delete']);
