<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Index/Index');
});

Route::get('login', [AuthController::class, 'create'])
    ->name('login');

Route::post('login', [AuthController::class, 'store'])
    ->name('login.store');

Route::delete('logout', [AuthController::class, 'destroy'])
    ->name('logout');

/*
Route::get('/fontos_oldal', [FontosController::class], 'fontos')
    ->name('fontos')
    ->middleware('auth');  (78)
*/
// login/create
// login
// login {login}

// create user
Route::resource('user-account', UserAccountController::class)
    ->only(['create', 'store']);