<?php

use App\Http\Controllers\AuthController\AuthController;
use App\Http\Controllers\StatementController\StatementController;
use App\Http\Controllers\UserController\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController\BookingController;
use App\Http\Controllers\ResourceController\ResourceController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['middleware' => ['role:client']], function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/allUsers', [UserController::class, 'getAllUsers']);
        Route::get('/showUser/{user}', [UserController::class, 'showUser']);
        Route::put('/updateUser', [UserController::class, 'updateUser']);

        Route::get('/allStatements', [StatementController::class, 'getAllStatements']);
        Route::get('/showStatement/{statement}', [StatementController::class, 'showStatement']);
        Route::post('/createStatement', [StatementController::class, 'createStatement']);
        Route::put('/updateStatement', [StatementController::class, 'updateStatement']);
        Route::delete('/deleteStatement/{id}', [StatementController::class, 'deleteStatement']);

        Route::post('/resources', [ResourceController::class, 'createResources']);
        Route::get('/resources', [ResourceController::class, 'getAllResources']);
        Route::post('/bookings', [BookingController::class, 'createBooking']);
        Route::get('/resources/{id}/bookings', [BookingController::class, 'getBookingsForResource']);
        Route::delete('/bookings/{id}', [BookingController::class, 'deleteBooking']);
    });
    Route::group(['middleware' => ['role:admin']], function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::post('assignRole/{user}', [UserController::class, 'assignRole']);
        Route::post('removeRole/{user}', [UserController::class, 'removeRole']);
        Route::delete('/deleteUser/{id}', [UserController::class, 'deleteUser']);
    });
});
