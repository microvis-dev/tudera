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

    private function columnExistsInTable($table_id, $column_name) {
        return WorkspaceColumn::where('table_id', $table_id)
                             ->where('name', strip_tags($column_name))
                             ->exists();
    }

    public function store(Request $request, $table_id) {
        $request->validate([
            'name' => 'string|max:255|min:3',
            'type' => 'required|in:string,integer,float,datetime,status'
        ]);

        try {
            $user = $request->user();
            $table = WorkspaceTable::findOrFail($table_id);
            $workspace = $table->workspace;

            if (!$user->workspaces()->find($workspace->id)) {
                return redirect()->back()->with('error', 'You do not have permission to modify this table.');
            }
            
            if ($this->columnExistsInTable($table_id, $request->input('name'))) {
                return redirect()->back()->with('error', 'A column with this name already exists in the table.');
            }

            $column = new WorkspaceColumn();
            $column->table_id = $table_id;
            $column->name = strip_tags($request->input('name'));
            $column->type = strip_tags($request->input('type'));
            $column->order = $table->columns->count() + 1;
            $column->save();

            return redirect()->back()->with('success', 'Column created successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceColumnController store: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $table_id, $column_id) {
        $request->validate([
            'name' => 'nullable|string|max:255|regex:/^\S.*$/',
            'order' => 'nullable|integer|min:1'
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

            if ($request->has('name') && $column->name !== strip_tags($request->name)) {
                if ($this->columnExistsInTable($table_id, $request->name)) {
                    return redirect()->back()->with('error', 'A column with this name already exists in the table.');
                }
                $column->name = strip_tags($request->name);
            }

            if ($request->has('order')) {
                $newOrder = intval($request->order);

                $nextToCol = WorkspaceColumn::where('table_id', $table->id)
                    ->where('order', $newOrder)
                    ->first();

                if ($nextToCol) {
                    $nextToCol->order = $column->order;
                    $nextToCol->save();

                    $column->order = $newOrder;
                }
            }

            $column->save();

            return redirect()->back()->with('success', 'Column updated successfully!');
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceColumnController update: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
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
