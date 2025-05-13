<?php

namespace App\Http\Controllers;

use App\Enums\WorkspaceColumnTypeEnum;
use App\Models\TableValue;
use App\Models\WorkspaceColumn;
use App\Models\WorkspaceTable;
use Illuminate\Http\Request;

use Exception;
use Log;

class TableValueController extends Controller
{
    private function getValidationRules($columnType) {
        try {
            $typeEnum = WorkspaceColumnTypeEnum::from($columnType);
            return $typeEnum->getValidationRule();
        } catch (Exception $e) {
            Log::warning("Unknown column type: $columnType");
            return 'required|string';
        }
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
        try {
            $columnType = WorkspaceColumn::where('id', $request->input('column_id'))->value('type');

            $validated = $request->validate([
                'value' => $this->getValidationRules($columnType),
                'column_id' => 'required|integer|exists:workspace_columns,id',
                'order' => 'nullable|integer|min:1'
            ]);


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
            Log::error('Error in TableValueController store: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $table_id, $value_id) {
        try {
            $columnType = WorkspaceColumn::where('id', TableValue::where('id', $value_id)->value('column_id'))->value('type');
            $request->validate([
                'new_value' => $this->getValidationRules($columnType),
                'order' =>'nullable|integer|min:1'
            ]);

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

            if ($request->has('order')) {
                $value->order = $request->order;
            }

            $value->save();

            return redirect()->back()->with('success', 'Value updated successfully.');
        } catch (Exception $e) {
            Log::error('Hiba TableValueController update: ' . $e->getMessage());
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
}
