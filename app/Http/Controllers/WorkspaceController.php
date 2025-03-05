<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Exception;
use Illuminate\Http\Request;
use Log;

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

    public function delete_workspace(Request $request, $id) {
        try {
            $user = $request->user();
    
            $workspace = $user->workspaces()->find($id);
    
            if (!$workspace) {
                return redirect()->back()->with('error', 'Workspace not found or you do not have permission to delete it.');
            }
    
            $workspace->delete();
    
            return redirect()->back()->with('success', 'Workspace deleted successfully.');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the workspace. Please try again later.');
        }
    }

    // csak levalaszt a workspace-rol ha van tobb user a workspacehez
    public function delete_workspace_2(Request $request, $id) {
        try {
            $user = $request->user();
    
            $workspace = $user->workspaces()->find($id);
    
            if (!$workspace) {
                return redirect()->back()->with('error', 'Workspace not found or you do not have permission to delete it.');
            }
    
            $user->workspaces()->detach($workspace->id);
    
            if ($workspace->users()->count() === 0) {
                $workspace->delete();
            }
    
            return redirect()->back()->with('success', 'Workspace removed successfully.');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while removing the workspace. Please try again later.');
        }
    }

    public function update_workspace(Request $request, $workspace_id) {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S.*$/'
        ]);
    
        try {
            $user = $request->user();
            $workspace = $user->workspaces()->find($workspace_id);
    
            if (!$workspace) {
                return redirect()->back()->with('error', 'Workspace not found or you do not have permission to edit it.');
            }
    
            $workspace->update(['name' => strip_tags($request->name)]);
    
            return redirect()->back()->with('success', 'Workspace name updated successfully.');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the workspace name.');
        }
    }
    
    
}
