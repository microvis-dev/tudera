<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceTableController;
use App\Models\Workspace;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('Index/Index');
})->middleware('auth')->name('index');

//auth
Route::resource('auth', AuthController::class)->only(['index', 'create', 'store', 'destroy']);
Route::get('auth/check_email', [AuthController::class, 'check_email'])->name('auth.check_email');

// setup
Route::any('setup/create-user', [SetupController::class, 'createUser'])
    ->name('setup.user.create');

// user
Route::resource('user', UserController::class)
    ->only(['store']);

Route::get('setup/create-workspace', [SetupController::class, 'createWorkspace'])
    ->name('setup.workspace.create');

Route::post('setup/create-workspace', [WorkspaceController::class, 'store'])
    ->name('setup.workspace.store');

// r + mw
Route::resource('workspaces', WorkspaceController::class)
    ->middleware('auth');

Route::resource('workspace.table', WorkspaceTableController::class)
    ->shallow();


