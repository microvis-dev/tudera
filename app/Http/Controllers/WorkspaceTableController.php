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
                foreach ($status_columns as $column) {
                    $column_statuses = StatusSelectValue::where('column_id', $column->id)->get()->toArray();

                    $formatted_status_options[] = [
                        'column_id' => $column->id,
                        'name' => $column->name,
                        'options' => $column_statuses
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

            return inertia('Workspace/Index', [
                'workspace_tables' => $workspace_tables,
                'workspace' => (int) $workspace_id
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController: ' . $e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while retrieving your workspace.');
        }
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
            return redirect()->route('workspaces')->with('error', $e->getMessage());
        }
    }

    private function tableExistsInWorkspace($workspace_id, $table_name) {
        return WorkspaceTable::where('workspace_id', $workspace_id)
                            ->where('name', strip_tags($table_name))
                            ->exists();
    }

    public function store(Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    try {
        $workspace_id = $request->workspace;
        $user = $request->user();
        $workspace = $user->workspaces()->find($workspace_id);

        if (!$workspace_id) { 
            return redirect()->route('dashboard.index')->with('error', 'Please select a workspace first.');
        }

        if (!$workspace) {
            return redirect()->route('dashboard.index')->with('error', 'You do not have access to this workspace.');
        }

        if ($this->tableExistsInWorkspace($workspace_id, $request->name)) {
            return redirect()->back()->with('error', 'A table with this name already exists in the workspace.');
        }

        DB::transaction(function () use ($request, $workspace_id) {
            WorkspaceTable::create([
                'name' => strip_tags($request->name),
                'workspace_id' => $workspace_id,
            ]);
        });

        return redirect()->route('dashboard.index')->with('success', 'Workspace table created successfully!');
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
        
        if ($table->name !== strip_tags($request->name) && $this->tableExistsInWorkspace($workspace_id, $request->name)) {
            return redirect()->back()->with('error', 'A table with this name already exists in the workspace.');
        }

        $table->name = strip_tags($request->name);
        $table->save();

        return redirect()->back()->with('success', 'Table updated successfully!');
    } catch (Exception $e) {
        Log::error('Hiba WorkspaceTableController update: ' . $e->getMessage());
        return redirect()->back()->with('error', $e->getMessage());
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

            return redirect()->route('dashboard.index')->with('success', 'Table deleted successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceTableController destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
