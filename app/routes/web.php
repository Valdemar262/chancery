<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login', ['showAuth' => false]);
})->name('login');

Route::get('/statements', function () {
    return view('statements', [
        'showAuth' => true,
        'isAuthenticated' => false,
        'userName' => null,
    ]);
})->name('statements');
