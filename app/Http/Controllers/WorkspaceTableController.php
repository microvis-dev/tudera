<?php

namespace App\Http\Controllers;

use App\Models\StatusSelectValue;
use App\Models\TableValue;
use App\Models\UsersToWorkspace;
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
            
            $column_ids = $table->columns->pluck('id')->toArray();
            $values = TableValue::whereIn('column_id', $column_ids)->get()->toArray();

            $status_columns = $table->columns->where('type', 'status');
            $formatted_status_options = [];

            if ($status_columns->count() > 0) {
                $default_statuses = StatusSelectValue::whereNull('column_id')->get()->toArray(); // and not None!
                
                foreach ($status_columns as $column) {
                    $column_statuses = StatusSelectValue::where('column_id', $column->id)->get()->toArray();
                    
                    $formatted_status_options[] = [
                        'column_id' => $column->id,
                        'name' => $column->name, 
                        'options' => array_merge($default_statuses, $column_statuses)
                    ];
                }
            }

            return inertia('WorkspaceTable/Index', [
                'workspace' => $workspace,
                'workspace_table' => $table,
                'columns' => $table->columns,
                'table_values' => $values,
                'status_options' => $formatted_status_options
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
                return redirect()->route('dashboard.index')->with('error', 'Please select a workspace first.');
            }
            
            if (!$workspace) {
                return redirect()->route('dashboard.index')->with('error', 'You do not have access to this workspace.');
            }
            
            DB::transaction(function () use ($request, $workspace_id) {
                $table = WorkspaceTable::create([
                    'name' => strip_tags($request->name),
                    'workspace_id' => $workspace_id,
                ]);
                
                $table->columns()->create([
                    'table_id' => $table->id,
                    'type' => 'string',
                    'name' => strip_tags($request->name),
                    'order' => 1,
                ]);
            });

            return redirect()->route('dashboard.index')->with('success', 'Workspace table created successfully!');
        } catch (Exception $e) {
            dd($e->getMessage());
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
