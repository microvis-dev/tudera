<?php

namespace App\Http\Controllers;

use App\Models\WorkspaceTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use Log;

class WorkspaceTableController extends Controller
{
    public function select(Request $request) {
        try {
            $user = $request->user();
            $workspaceId = $request->input('workspace_id');
            $workspace = $user->workspaces()->find($workspaceId);
            
            if (!$workspaceId) {
                return redirect()->route('workspaces')->with('error', 'Invalid workspace selected.');
            }
            
            if (!$workspace) {
                return redirect()->route('workspaces')->with('error', 'You do not have access to this workspace.');
            }
            
            $request->session()->put('selected_workspace_id', $workspaceId);
            
            return redirect()->route('workspace-table.index');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController select: ' . $e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while selecting workspace.');
        }
    }

    public function index(Request $request) {
        try {
            $selectedWorkspaceId = $request->session()->get('selected_workspace_id');
            $workspaceTables = WorkspaceTable::where('workspace_id', $selectedWorkspaceId)->get();
            
            if (!$selectedWorkspaceId) {
                return redirect()->route('workspaces')->with('error', 'Please select a workspace first.');
            }
                        
            return inertia('Workspace/Index', [
                'workspace_tables' => $workspaceTables,
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController: ' . $e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while retrieving your workspace.');
        }
    }
    
    public function create(Request $request) {
        try {
            $selectedWorkspaceId = $request->session()->get('selected_workspace_id');
            
            if (!$selectedWorkspaceId) {
                return redirect()->route('workspaces')->with('error', 'Please select a workspace first.');
            }
            
            return inertia('Workspace/Create');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController create: ' . $e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while loading the create form.');
        }
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        try {
            $selectedWorkspaceId = $request->session()->get('selected_workspace_id');
            $user = $request->user();
            $workspace = $user->workspaces()->find($selectedWorkspaceId);
            
            if (!$selectedWorkspaceId) { // 3
                return redirect()->route('workspaces')->with('error', 'Please select a workspace first.');
            }
            
            if (!$workspace) {
                return redirect()->route('workspaces')->with('error', 'You do not have access to this workspace.');
            }
            
            DB::transaction(function () use ($request, $selectedWorkspaceId) {
                WorkspaceTable::create([
                    'name' => strip_tags($request->name),
                    'workspace_id' => $selectedWorkspaceId,
                ]);
            });

            return redirect()->route('workspace-table.index')->with('success', 'Workspace table created successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the table.');
        }
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S.*$/',
        ]);
        
        try {
            $table = WorkspaceTable::findOrFail($id);
            $selectedWorkspaceId = $request->session()->get('selected_workspace_id');
            $user = $request->user();
            $workspace = $user->workspaces()->find($selectedWorkspaceId);

            if ($table->workspace_id != $selectedWorkspaceId) {
                return redirect()->route('workspaces')->with('error', 'You do not have permission to update this table.');
            }
            
            if (!$workspace) {
                return redirect()->route('workspaces')->with('error', 'You do not have access to this workspace.');
            }
            
            $table->name = strip_tags($request->name);
            $table->save();
            
            return redirect()->back()->with('success', 'Table updated successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the table.');
        }
    }
    
    public function destroy(Request $request, $id) {
        try {
            $table = WorkspaceTable::findOrFail($id);
            $selectedWorkspaceId = $request->session()->get('selected_workspace_id');
            $user = $request->user();
            $workspace = $user->workspaces()->find($selectedWorkspaceId);
            
            if ($table->workspace_id != $selectedWorkspaceId) {
                return redirect()->route('workspaces')->with('error', 'You do not have permission to delete this table.');
            }            
            
            if (!$workspace) {
                return redirect()->route('workspaces')->with('error', 'You do not have access to this workspace.');
            }
            
            $table->delete();
            
            return redirect()->back()->with('success', 'Table deleted successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the table.');
        }
    }
}
