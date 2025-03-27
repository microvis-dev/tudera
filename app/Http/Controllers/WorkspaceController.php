<?php

namespace App\Http\Controllers;

use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use App\Models\WorkspaceTable;
use App\Models\WorkspaceColumn;
use App\Models\WorkspaceRow;
use App\Models\TableValue;
use Exception;
use Illuminate\Http\Request;
use Log;
use Illuminate\Support\Facades\DB;

class WorkspaceController extends Controller
{
    function index(Request $request) {
        try {
            $user = $request->user();
            $user_workspaces = $user->workspaces()->get(['name', 'workspace_id'])->toArray();

            return inertia('Workspaces/Index', [
                'user_workspaces' => $user_workspaces
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->intended('/')->with('error', 'An error occurred while retrieving your workspaces. Please try again later.');
        }
    }

    function create(Request $request) {
        $user = $request->user();
        $workspaces = $user->workspaces;

        if ($workspaces->count() == 0) {
            return inertia('Setup/CreateWorkspace');
        }
        return inertia('Workspaces/Create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        DB::transaction(function () use ($request) {
            $workspace = new Workspace();
            $workspace->name = $request->input('name');
            $workspace->save();
            
            $users_to_workspace = new UsersToWorkspace();

            $users_to_workspace->workspace_id = $workspace->id;
            $users_to_workspace->user_id = $request->user()->id;
            $users_to_workspace->save();

            // create leads
            $this->createDefaultLeadsTable($workspace);

        });

        return redirect()->route('index')->with('success', 'Workspace created successfully!');
    }

    function show() {
        
    }

    public function destroy(Request $request, $id) {
        try {
            $user = $request->user();
    
            $workspace = $user->workspaces()->find($id);
    
            if (!$workspace) {
                return redirect()->back()->with('error', 'Workspace not found or you do not have permission to delete it.');
            }
            
            if ($workspace->users()->count() == 1) {
                $workspace->delete();
            } else {
                $user->workspaces()->detach($workspace->id);
            }

            return redirect()->back()->with('success', 'Workspace deleted successfully.');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the workspace. Please try again later.');
        }
    }

    public function update(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S.*$/'
        ]);
        $workspace_id = $request->workspace_id;
    
        try {
            $user = $request->user();
            $workspace = $user->workspaces()->find($workspace_id);
    
            if (!$workspace) {
                return redirect()->back()->with('error', 'Workspace not found or you do not have permission to edit it.');
            }
    
            $workspace->update(['name' => strip_tags($request->name)]);
    
            return redirect()->back()->with('success', 'Workspace name updated successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the workspace name.');
        }
    }


    private function createDefaultLeadsTable(Workspace $workspace)
    {
        try {
            $leadsTable = new WorkspaceTable();
            $leadsTable->workspace_id = $workspace->id;
            $leadsTable->name = 'Leads';
            $leadsTable->save();        
            // todo fill
        } catch (Exception $e) {
            Log::error('Error creating default Leads table: ' . $e->getMessage());
            dd($e->getMessage());
            throw $e;
        }
    }

    
}
