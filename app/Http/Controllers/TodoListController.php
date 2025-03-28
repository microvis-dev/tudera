<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Exception;
use Log;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function show() {

    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'string|max:30',
            'description' => 'nullable|string|max:500',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        try {
            $user = $request->user();
            TodoList::create([
                'user_id' => $user->id,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'is_done' => false,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

            return redirect()->back()->with('success', 'ToDo item created successfully!');
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->route('dashboard.index')->with('error', 'An error occurred while updating the workspace name.');
        }
    }

    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'string|max:30',
            'description' => 'nullable|string|max:500',
            'is_done' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        try {
            $user = $request->user();
            $todo = TodoList::where('user_id', $user->id)->findOrFail($id);

            $todo->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'is_done' => $request->input('is_done'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ]);

            return redirect()->route('dashboard.index')->with('success', 'ToDo item updated successfully!');
        } catch (Exception $e) {
            Log::error('Error updating ToDo item: ' . $e->getMessage());
            return redirect()->route('dashboard.index')->with('error', 'An error occurred while updating the ToDo item.');
        }
    }

    public function destroy(Request $request, $id) {
        try {
            $user = $request->user();
            $todo = TodoList::where('user_id', $user->id)->findOrFail($id);

            $todo->delete();

            return redirect()->route('dashboard.index')->with('success', 'ToDo item deleted successfully!');
        } catch (Exception $e) {
            Log::error('Error deleting ToDo item: ' . $e->getMessage());
            return redirect()->route('dashboard.index')->with('error', 'An error occurred while deleting the ToDo item.');
        }
    }
}
