<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\UsersToWorkspaceController;

Route::middleware(['web', 'auth:sanctum'])->group(function () {
    Route::prefix('v1/users-to-workspace')->group(function () {
        Route::get('/datatable', [UsersToWorkspaceController::class, 'datatable']);
        Route::get('/{workspace}/create-invite', [UsersToWorkspaceController::class, 'createInvite'])->name('workspace.invite.create');
    });

    Route::prefix('v1/users')->group(function () {
        Route::post('/profile-picture', [\App\Http\Controllers\Api\V1\UserController::class, 'updateProfilePicture'])->name('user.picture.update');
    });
});
