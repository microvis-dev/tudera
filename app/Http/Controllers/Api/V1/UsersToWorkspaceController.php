<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Search\UsersToWorkspaceSearch;
use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use App\Models\WorkspaceInvites;
use Illuminate\Http\Request;

class UsersToWorkspaceController extends Controller
{
    public function datatable(Request $request)
    {
        $usersSearch = new UsersToWorkspaceSearch();
        $users = $usersSearch->search($request);

        $data = [];
        foreach ($users->get() as $user) {
            $data[] = [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->pivot->role,
            ];
        }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $usersSearch->total,
            'recordsFiltered' => count($data),
            'data' => $data
        ]);
    }


    public function createInvite(Request $request)
    {
        $workspace = Workspace::findOrFail($request->route('workspace'));

        $invitation = new WorkspaceInvites([
            'workspace_id' => $workspace->id,
        ]);

        if ($invitation->save()) {
            return response()->json([
                'invite_id' => $invitation->invite_id,
            ], 201);
        } else {
            return response()->json(['message' => 'Failed to send invitation'], 500);
        }
    }
}
