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
use App\Http\Controllers\SetupController;
use App\Http\Controllers\TodoListController;

// index
Route::resource('/', IndexController::class)->only('index'); // ->middleware('auth')

//auth
Route::resource('auth', AuthController::class)->only(['index', 'create', 'store', 'destroy']);
Route::get('auth/check_email', [AuthController::class, 'check_email'])->name('auth.check_email');

// user
Route::resource('user', UserController::class)
    ->only(['store']);

// setup
// Route::resource('setup.workspace', WorkspaceController::class)->only(['create', 'store']);
Route::get('setup/workspace/create', [WorkspaceController::class, 'create'])
    ->name('setup.workspace.create');

Route::post('setup/workspace', [WorkspaceController::class, 'store'])
    ->name('setup.workspace.store');

Route::resource('signup', SetupController::class)->only(['create']);

// r + mw
Route::resource('workspaces', WorkspaceController::class)
    ->middleware('auth');

Route::resource('workspace.table', WorkspaceTableController::class)
    ->shallow();


//col
Route::resource('table.columns', WorkspaceColumnController::class)
    ->only(['index', 'create', 'store', 'destroy', 'update']);

//row
Route::resource('table.rows', WorkspaceRowController::class)
    ->only(['index', 'create', 'store', 'destroy', 'update']);

Route::resource('table.values', TableValueController::class)
    ->only(['create', 'store', 'update', 'destroy']);

//calendar
Route::resource('calendar', CalendarController::class)
    ->only(['index']);
//dashboard
//calendar
Route::resource('dashboard', DashboardController::class)
    ->only(['index']);

// ToDo list
Route::resource('todolist', TodoListController::class)
    ->only(['show', 'store', 'update', 'destroy']);