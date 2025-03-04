<?php

namespace App\Http\Controllers;

use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use Exception;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index(Request $request) {
        $user_workspaces = [];
        
        try {
            //throw new Exception("error");
            $user_id = $request->user()->id;
            $workspace_ids = UsersToWorkspace::where('user_id', $user_id)->pluck('workspace_id');
            
            $user_workspaces = Workspace::whereIn('id', $workspace_ids)->get(['name']);
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Failed to retrieve workspaces.');
        }
        

        return inertia('Workspaces/Index', [
            'user_workspaces' => $user_workspaces
        ]);
    }
}
