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
        $columns = WorkspaceTable::findOrFail($table_id)->columns;
        return response()->json($columns);
    }

    public function store(Request $request, $table_id) {
        $request->validate([
            'name' => 'string|max:255|min:3',
            'type' => 'string'
        ]);

        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            
            if (false) { // $table->workspace->user_id !== $user->id
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $column = new WorkspaceColumn();
            $column->table_id = $table_id;
            $column->name = $request->input('name');
            $column->type = $request->input('type');
            $column->order = $table->columns->count() + 1;
            $column->save();
            
            return redirect()->route('workspaces.index', $table)->with('success', 'Column created successfully.');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceColumnController update: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while creating the column.');
        }
    }

    public function create(Request $request, $table_id) {
        $table = WorkspaceTable::findOrFail($table_id);
        $user = $request->user();

        if (false) {
            return redirect()->back()->with('error', 'You do not have permission to modify this table.');
        }
        
        return inertia('WorkspaceTable/CreateColumn', [
            'table' => $table
        ]);
    }
}