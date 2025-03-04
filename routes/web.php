<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Index/Index');
})->middleware('auth');

Route::get('auth', [AuthController::class, 'create'])
    ->name('auth');

Route::get('auth.check_email', [AuthController::class, 'check_email'])
    ->name('auth.check_email');

Route::post('auth', [AuthController::class, 'store'])
    ->name('auth.store');

Route::delete('logout', [AuthController::class, 'destroy'])
    ->name('logout');

// login/create
// login
// login {login}

// setup
Route::get('setup/create-user', [SetupController::class, 'createUser'])
    ->name('setup.user.create');

Route::get('setup/create-workspace', [SetupController::class, 'createWorkspace'])
    ->name('setup.workspace.create');
