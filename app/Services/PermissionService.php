<?php

namespace App\Services;

use App\Enums\RolesEnum;
use App\Models\User;
use App\Models\Workspace;

class PermissionService
{
    public static function userHasWorkspacePerm(User $user, Workspace $workspace, array $roles): bool
    {
        $currentWorkspace = WorkspaceService::get($user);
        if ($currentWorkspace && $currentWorkspace->id !== $workspace->id) {
            if (!WorkspaceService::change($user, $workspace)) return false;
        }
        $hasRole = $user->hasAnyRole($roles);
        WorkspaceService::change($user, $currentWorkspace);
        return $hasRole;
    }
}
?>
