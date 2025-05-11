<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UsersToWorkspace;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;

class UsersToWorkspaceController extends Controller
{
    public function show(Request $request)
    {
        $workspace = $request->route('id');
        $user = $request->route('user');

        $usersToWorkspace = UsersToWorkspace::where('workspace_id', $workspace)
            ->where('user_id', $user)
            ->first();

        $user = $usersToWorkspace->user()->get()[0];
        $workspace = $usersToWorkspace->workspace()->get()[0];
        $current_workspace = WorkspaceService::get($user);
        if ($workspace->id !== $current_workspace?->id) {
            setPermissionsTeamId($workspace->id);
        }
        $selected_role = $user->roles->first()->id ?? null;
        $roles = Role::all();
        setPermissionsTeamId($current_workspace?->id);
        if ($usersToWorkspace) {
            return inertia("Workspaces/UsersToWorkspace/Edit", [
                'workspace_user' => $user,
                'workspace' => $workspace,
                'roles' => $roles,
                'selected_role' => $selected_role,
            ]);
        }
        session()->flash('error', 'User not found in this workspace');
        return redirect(route('dashboard.index'));
    }

    public function update(Request $request)
    {
        $workspace_id = $request->route('id');
        $user_id = $request->route('user');
        $role_id = $request->post('role');
        $workspace = UsersToWorkspace::where('workspace_id', $workspace_id)
            ->where('user_id', $user_id)
            ->first();
        if ($workspace) {
            if ($user = $workspace->user()->get()[0] ?? null) {
                $user->syncRoles([$role_id]);
                session()->flash('success', 'User role updated successfully');
            } else {
                session()->flash('error', 'User not found in this workspace');
            }
        }
        return redirect()->back();
    }

    public function destroy(Request $request)
    {
        $workspace_id = $request->route('id');
        $user_id = $request->route('user');
        $current_user = auth()->user();
        $workspace = UsersToWorkspace::where('workspace_id', $workspace_id)
            ->where('user_id', $user_id)
            ->first();
        if ($workspace) {
            $workspace->delete();
            session()->flash('success', 'User removed from workspace successfully');
        }
        return redirect()->back();
    }
}
