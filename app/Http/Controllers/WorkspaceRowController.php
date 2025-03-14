<?php

namespace App\Http\Controllers;

use App\Models\WorkspaceTable;
use Illuminate\Http\Request;
use App\Models\WorkspaceRow;
use Exception;
use Log;

class WorkspaceRowController extends Controller
{
    public function index() {
        
    }

    public function show() {
        
    } 

    public function create(Request $request, $table_id) {
        $user = $request->user();
        $table = WorkspaceTable::findOrFail($table_id);
        $workspace = $table->workspace;
    
        if (!$user->workspaces()->find($workspace->id)) {
            return redirect()->route('workspaces')->with('error', 'You do not have permission to modify this table.');
        }

        return inertia('WorkspaceTable/CreateRow', [
            'table' => $table
        ]);
    }

    public function store(Request $request, $table_id) {
        $request->validate([
            'name' => 'string|max:255|min:3',
        ]);

        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $row = new WorkspaceRow();
            $row->table_id = $table_id;
            $row->name = strip_tags($request->input('name'));
            $row->order = $request->input('order', $table->rows->count() + 1);
            $row->save();
            
            return redirect()->route('workspace.table.index', ['workspace' => $workspace->id, 'table' => $table->id])
                ->with('success', 'Row created successfully.');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceRowController store: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the row.');
        }
    }


    public function update(Request $request, $table_id, $row_id) {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^\S.*$/',
        ]);
        
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $row = WorkspaceRow::findOrFail($row_id);
            $workspace = $table->workspace;
            
            if ($row->table_id != $table->id) {
                return redirect()->back()->with('error', 'You do not have permission to update this row.');
            }
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $row->name = strip_tags($request->name);
            $row->save();
            
            return redirect()->back()->with('success', 'Row updated successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceRowController update: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the row.');
        }
    }

    public function destroy(Request $request, $table_id, $row_id) {
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $row = WorkspaceRow::findOrFail($row_id);
            $workspace = $table->workspace;
            
            if ($row->table_id != $table->id) {
                return redirect()->back()->with('error', 'You do not have permission to delete this row.');
            }
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $row->delete();
            
            return redirect()->back()->with('success', 'Row deleted successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceRowController destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the row.');
        }
    }
}
