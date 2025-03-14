<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\WorkspaceTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Exception;
use Log;

class WorkspaceTableController extends Controller
{
    public function show(Request $request) {
        try {
            $user = $request->user();
            $table_id = $request->table;
            $table = WorkspaceTable::find($table_id);
            
            $workspace_id = $table->workspace_id;
            $workspace = $user->workspaces()->find($workspace_id);
            
            if (!$workspace_id) {
                return redirect()->route('workspaces')->with('error', 'Invalid workspace selected.');
            }
            
            if (!$workspace) {
                return redirect()->route('workspaces')->with('error', 'You do not have access to this workspace.');
            }
            
            return inertia('WorkspaceTable/Index', [
                'workspace' => $workspace,
                'workspace_table' => $table,
                'columns' => $table->columns,
                'rows' => $table->rows
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController select: ' . $e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while selecting workspace.');
        }
    }

    public function index(Request $request) {
        try {
            $workspace_id = $request->workspace;
            $workspace_tables = WorkspaceTable::where('workspace_id', $workspace_id)->get();
            
            if (!$workspace_id) {
                return redirect()->route('workspaces')->with('error', 'Please select a workspace first.');
            }

            if(!$this->find($workspace_id)) {
                return redirect()->route('workspaces')->with('error', 'You do not have access to this workspace.');
            }
                        
            return inertia('Workspace/Index', [
                'workspace_tables' => $workspace_tables,
                'workspace' => (int) $workspace_id
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController: ' . $e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while retrieving your workspace.');
        }
    }

    private function find($workspace_id) {
        return true;
    }
    
    public function create(Request $request) {
        try {
            $workspace_id = $request->workspace;
            
            if (!$workspace_id) {
                return redirect()->route('workspaces')->with('error', 'Please select a workspace first.');
            }
            
            return inertia('Workspace/Create', [
                "workspace" => (int) $workspace_id
            ]);
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
            $workspace_id = $request->workspace;
            $user = $request->user();
            $workspace = $user->workspaces()->find($workspace_id);
            
            if (!$workspace_id) { // 3
                return redirect()->route('workspaces')->with('error', 'Please select a workspace first.');
            }
            
            if (!$workspace) {
                return redirect()->route('workspaces')->with('error', 'You do not have access to this workspace.');
            }
            
            DB::transaction(function () use ($request, $workspace_id) {
                WorkspaceTable::create([
                    'name' => strip_tags($request->name),
                    'workspace_id' => $workspace_id,
                ]);
            });

            return redirect()->route('workspace.table.index', ['workspace' => $workspace_id])->with('success', 'Workspace table created successfully!');
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
            $workspace_id = $request->workspace;
            $user = $request->user();
            $workspace = $user->workspaces()->find($workspace_id);

            if ($table->workspace_id != $workspace_id) {
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
    
    public function destroy(Request $request) {
        try {
            $table_id = $request->table;
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace_id = $request->workspace;
            $user = $request->user();
            $workspace = $user->workspaces()->find($workspace_id);
            
            if ($table->workspace_id != $workspace_id) {
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
