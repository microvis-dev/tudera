<?php

namespace App\Http\Controllers;

use App\Models\StatusSelectValue;
use Illuminate\Http\Request;

class StatusSelectValueController extends Controller
{
    public function store(Request $request) {
        $validated = $request->validate([
            'column_id' => 'nullable|exists:workspace_columns,id', 
            'value' => 'required|string',
        ]);
        
        $query = StatusSelectValue::where('value', $validated['value']);
        if ($validated['column_id'] === null) {
            $query->whereNull('column_id');
        } else {
            $query->where('column_id', $validated['column_id']);
        }
        
        $existingValue = $query->first();
        
        if ($existingValue) {
            return redirect()->back()->with("message", "");
        }
        
        StatusSelectValue::create([
            'column_id' => $validated['column_id'],
            'value' => $validated['value'],
        ]);
        
        return redirect()->back()->with("message", "");

    }

    public function update(Request $request) {
        
    }

    public function destroy(Request $request) {
        
    }
}
