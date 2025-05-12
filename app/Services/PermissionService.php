<?php

namespace App\Services;

use App\Enums\RolesEnum;
use App\Models\User;
use App\Models\Workspace;

class PermissionService
{
    public static function userHasWorkspacePerm(User $user, Workspace $workspace, $role): bool
    {
        $currentWorkspace = WorkspaceService::get($user);
        if ($currentWorkspace && $currentWorkspace->id !== $workspace->id) {
            if (!WorkspaceService::change($user, $workspace)) return false;
        }
        $hasRole = $user->hasRole($role);
        WorkspaceService::change($user, $currentWorkspace);
        return $hasRole;
    }
}
?>
