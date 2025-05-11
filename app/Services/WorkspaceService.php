<?php

namespace App\Services;

use App\Models\User;
use App\Models\Workspace;

class WorkspaceService
{
    public const WORKSPACE_SESSION = 'current_workspace_id';
    public static function hasAccess(User $user, Workspace $workspace) : bool
    {
        $exists = $user->workspaces()->where('workspace_id', $workspace->id)->exists();
        return $exists;
    }

    public static function get(User $user) : ?Workspace
    {
        if ($current = session(self::WORKSPACE_SESSION)) {
            $workspace = Workspace::find($current);
            if (self::hasAccess($user, $workspace)) {
                return $workspace;
            }
        }
        return null;
    }

    public static function change(User $user, Workspace $workspace) : bool
    {
        if (!self::hasAccess($user, $workspace)) {
            session()->flash('error', 'You do not have access to this workspace');
            return false;
        } else {
            session([self::WORKSPACE_SESSION => $workspace->id]);
            setPermissionsTeamId($workspace->id);
            return true;
        }
    }
}
?>
