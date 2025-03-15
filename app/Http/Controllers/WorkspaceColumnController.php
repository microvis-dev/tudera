<?php

namespace App\Http\Controllers;

use App\Models\WorkspaceColumn;
use App\Models\WorkspaceTable;
use Illuminate\Http\Request;
use Exception;
use Log;
use Illuminate\Support\Facades\Auth;

class WorkspaceColumnController extends Controller
{
    public function index(Request $request, $table_id) {
        
    }

    function show() {
        
    }

    public function create(Request $request, $table_id) {
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;
        
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->route('workspaces')->with('error', 'You do not have permission to modify this table.');
            }
            
            return inertia('WorkspaceTable/CreateColumn', [
                'table' => $table
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceColumnController create: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while loading the create form.');
        }
    }

    public function store(Request $request, $table_id) {
        $request->validate([
            'name' => 'string|max:255|min:3',
            'type' => 'required|in:string,integer,float,datetime,status,ref'
        ]);

        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $column = new WorkspaceColumn();
            $column->table_id = $table_id;
            $column->name = strip_tags($request->input('name'));
            $column->type = strip_tags($request->input('type'));
            $column->order = $table->columns->count() + 1;
            $column->save();
            
            return redirect()->route('workspace.table.index', ['workspace' => $workspace->id])
                ->with('success', 'Column created successfully.');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceColumnController store: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the column.');
        }
    }

    public function update(Request $request, $table_id, $column_id) {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S.*$/',
        ]);
        
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $column = WorkspaceColumn::findOrFail($column_id);
            $workspace = $table->workspace;
            
            if ($column->table_id != $table->id) {
                return redirect()->back()->with('error', 'You do not have permission to update this column.');
            }
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $column->name = strip_tags($request->name);
            $column->save();
            
            return redirect()->back()->with('success', 'Column updated successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceColumnController update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the column.');
        }
    }

    public function destroy(Request $request, $table_id, $column_id) {
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $column = WorkspaceColumn::findOrFail($column_id);
            $workspace = $table->workspace;
            
            if ($column->table_id != $table->id) {
                return redirect()->back()->with('error', 'You do not have permission to delete this column.');
            }
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $column->delete();
            
            return redirect()->back()->with('success', 'Column deleted successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceColumnController destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the column.');
        }
    }
}
