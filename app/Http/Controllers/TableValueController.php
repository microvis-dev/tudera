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
        return;
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
        $validated = $request->validate([
            'value' => 'required',
            'column_id' => 'required|integer|exists:workspace_columns,id',
            'order' => 'nullable|integer|min:0'
        ]);

    
        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;
            
            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            $column = $table->columns()->find($validated['column_id']);
            if (!$column) {
                return redirect()->back()->with('error', 'The column does not belong to this table.');
            }

            $maxOrder = TableValue::where('column_id', $validated['column_id'])->max('order') ?? 0;
            
            TableValue::create([
                'column_id' => $validated['column_id'],
                'value' => $validated['value'],
                'order' => (isset($validated['order']) ? $validated['order'] : $maxOrder + 1)
            ]);

            return redirect()->back()->with('success', 'Value added successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error('Error in TableValueController store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the value.');
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
            
            $column = $table->columns()->find($value->column_id);
            
            if (!$column) {
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
            
            $column = $table->columns()->find($value->column_id);
            
            if (!$column) {
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
