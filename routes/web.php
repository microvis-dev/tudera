<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\TableValueController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceColumnController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceTableController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\StatusSelectValueController;
use App\Http\Controllers\TodoListController;

// index
Route::resource('/', IndexController::class)->only('index')->middleware('auth');

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
Route::get('workspaces/get', [WorkspaceController::class, 'get'])->name('workspaces.get')->middleware('auth');
Route::post('/workspaces/change', [WorkspaceController::class, 'change'])->name('workspaces.change')->middleware('auth');
Route::get('workspaces/{id}/settings', [WorkspaceController::class, 'settings'])->name('workspaces.settings')->middleware('auth');
// users to workspace
Route::get('workspaces/join', [\App\Http\Controllers\UsersToWorkspaceController::class, 'create'])
    ->name('workspaces.user.create')
    ->middleware('auth');

Route::post('workspaces/join', [\App\Http\Controllers\UsersToWorkspaceController::class, 'store'])
    ->name('workspaces.user.store')
    ->middleware('auth');

Route::get('workspaces/{id}/users/{user}', [\App\Http\Controllers\UsersToWorkspaceController::class, 'show'])
    ->name('workspaces.user.show')
    ->middleware('auth');

Route::post('workspaces/{id}/users/{user}/update', [\App\Http\Controllers\UsersToWorkspaceController::class, 'update'])
    ->name('workspaces.user.update')
    ->middleware('auth');

Route::delete('workspaces/{id}/users/{user}', [\App\Http\Controllers\UsersToWorkspaceController::class, 'destroy'])
    ->name('workspaces.user.destroy')
    ->middleware('auth');

// workspace table
Route::resource('workspace.table', WorkspaceTableController::class)
    ->shallow()->middleware('auth');

// col
Route::resource('table.columns', WorkspaceColumnController::class)
    ->only(['store', 'destroy', 'update']);
Route::resource('table.values', TableValueController::class)
    ->only(['store', 'update', 'destroy']);

// calendar
Route::resource('calendar', CalendarController::class)
    ->only(['index', 'update', 'store', 'destroy']);

// dashboard
Route::resource('dashboard', DashboardController::class)
    ->only(['index'])
    ->middleware('auth');

// todo list
Route::resource('todolist', TodoListController::class)
    ->only(['store', 'update', 'destroy']);

// settings
Route::resource('settings', SettingsController::class)
    ->only(['index'])->middleware('auth');

// status select value
Route::resource('selectvalues', StatusSelectValueController::class)
    ->only(['store', 'destroy']);

// notifications
Route::resource('notification', NotificationController::class)
    ->only(['store', 'destroy']);
