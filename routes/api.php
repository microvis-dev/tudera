<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UsersToWorkspaceController;

Route::prefix('v1/users-to-workspace')->group(function () {
    Route::get('/datatable', [UsersToWorkspaceController::class, 'datatable']);
});
