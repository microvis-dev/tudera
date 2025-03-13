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

Route::get('auth', [AuthController::class, 'create'])
    ->name('auth');

Route::get('auth/check_email', [AuthController::class, 'check_email'])
    ->name('auth.check_email');

Route::post('auth', [AuthController::class, 'store'])
    ->name('auth.store');

Route::delete('logout', [AuthController::class, 'destroy'])
    ->name('logout');

// login/create
// login
// login {login}

// setup
//Route::any('setup/create-user', [SetupController::class, 'createUser'])
  //  ->name('setup.user.create');
// user
Route::resource('user', UserController::class)
    ->only(['store']);

Route::get('setup/create-workspace', [SetupController::class, 'createWorkspace'])
    ->name('setup.workspace.create');

Route::post('setup/create-workspace', [WorkspaceController::class, 'store_workspace'])
    ->name('setup.workspace.store');

// r + mw
Route::get('workspaces', [WorkspaceController::class, 'index'])
    ->name('workspaces');

Route::delete('workspaces/{id}', [WorkspaceController::class, 'delete_workspace'])
    ->name('workspace.delete');

Route::put('workspaces/{id}', [WorkspaceController::class, 'update_workspace'])
    ->name('workspace.update');

Route::get('workspaces/create-workspace', [WorkspaceController::class, 'create_workspace'])
    ->name('workspace.create');

Route::post('workspaces/create-workspace', [WorkspaceController::class, 'store_workspace'])
    ->name('workspace.store');

Route::resource('workspace.table', WorkspaceTableController::class)
    ->shallow();


