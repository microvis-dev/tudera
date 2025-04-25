<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;

class WorkspaceService
{
    public static function change(User $user, Workspace $workspace) : bool
    {
        $hasAccess = $user->workspaces()->where('workspace_id', $workspace->id)->exists();

        if (!$hasAccess) {
            return false;
        }

        session(['current_workspace_id' => $workspace->id]);
        setPermissionsTeamId($workspace->id);

        return true;
    }
}
?>
