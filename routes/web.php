<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\TableValueController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceColumnController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceRowController;
use App\Http\Controllers\WorkspaceTableController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\TodoListController;

// index
Route::resource('/', IndexController::class)->only('index'); // ->middleware('auth')

//auth
Route::resource('auth', AuthController::class)->only(['index', 'create', 'store', 'destroy']);
Route::get('auth/check_email', [AuthController::class, 'check_email'])->name('auth.check_email');

// user
Route::resource('user', UserController::class)
    ->only(['store', 'update', 'destroy']);

// setup
Route::get('setup/workspace/create', [WorkspaceController::class, 'create'])
    ->name('setup.workspace.create');

Route::post('setup/workspace', [WorkspaceController::class, 'store'])
    ->name('setup.workspace.store');

Route::resource('signup', SetupController::class)->only(['create']);


// workspaces
Route::resource('workspaces', WorkspaceController::class)
    ->middleware('auth');

// workspace table
Route::resource('workspace.table', WorkspaceTableController::class)
    ->shallow()->middleware('auth');


// col
Route::resource('table.columns', WorkspaceColumnController::class)
    ->only(['index', 'create', 'store', 'destroy', 'update']);
Route::resource('table.values', TableValueController::class)
    ->only(['create', 'store', 'update', 'destroy']);

// calendar
Route::resource('calendar', CalendarController::class)
    ->only(['index', 'create', 'update', 'store', 'destroy']);

// dashboard
Route::resource('dashboard', DashboardController::class)
    ->only(['index']);

// todo list
Route::resource('todolist', TodoListController::class)
    ->only(['show', 'store', 'update', 'destroy']);

// settings
Route::resource('settings', SettingsController::class)
    ->only(['index'])->middleware('auth');