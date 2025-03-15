<?php

namespace App\Http\Controllers;

use App\Models\TableValue;
use App\Models\WorkspaceTable;
use Illuminate\Http\Request;

use Exception;
use Log;

class TableValueController extends Controller
{

    private function check() {
        return true;
    }

    public function create(Request $request, $table_id) { 
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;
        
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->route('workspaces')->with('error', 'You do not have permission to modify this table.');
            }
            
            return inertia('WorkspaceTable/CreateValue', [
                'table' => $table
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceColumnController create: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while loading the create form.');
        }   
    }

    public function store(Request $request, $table_id) {
        // dd($request->column); ha useForm obj
        // dd($request->input('row'), $request->input('column'), $request->input('table')); ha param

        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $request->validate([
                'value' => 'required'
            ]);
            
            $row_id = $request->row_id;
            $column_id = $request->column_id;
            
            $row = $table->rows()->find($row_id);
            $column = $table->columns()->find($column_id);
            
            if (!$row || !$column) {
                return redirect()->back()->with('error', 'Invalid row or column.');
            }
            
            $tableValue = TableValue::firstOrNew([ // !
                'row_id' => $row_id,
                'column_id' => $column_id
            ]);
            
            $tableValue->value = $request->value;
            $tableValue->save();
            
            return redirect()->back()->with('success', 'Value saved successfully.');
        } catch (Exception $e) {
            Log::error('Hiba TableValueController store: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->route('workspaces')->with('error', 'An error occurred while saving.');
        }
    }

    public function update(Request $request, $table_id, $value_id) {
        $request->validate([
            'new_value' => 'required',
        ]);
        
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;
            $value = TableValue::findOrFail($value_id);
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $row = $table->rows()->find($value->row_id);
            $column = $table->columns()->find($value->column_id);
            
            if (!$row || !$column) {
                return redirect()->back()->with('error', 'Invalid value - not associated with this table.');
            }
            
            $value->value = $request->new_value;
            $value->save();
            
            return redirect()->back()->with('success', 'Value updated successfully.');
        } catch (Exception $e) {
            Log::error('Hiba TableValueController update: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the value.');
        }
    }

    public function destroy(Request $request, $table_id, $value_id) {
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;
            $value = TableValue::findOrFail($value_id);
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $row = $table->rows()->find($value->row_id);
            $column = $table->columns()->find($value->column_id);
            
            if (!$row || !$column) {
                return redirect()->back()->with('error', 'Invalid value - not associated with this table.');
            }
            
            $value->delete();
            
            return redirect()->back()->with('success', 'Value deleted successfully.');
        } catch (Exception $e) {
            Log::error('Hiba TableValueController destroy: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the value.');
        }
    }

    public function show(Request $request) {
        
    }
}
