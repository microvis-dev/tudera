<?php

namespace App\Http\Controllers;

use App\Models\StatusSelectValue;
use Illuminate\Http\Request;
use Exception;

class StatusSelectValueController extends Controller
{
    private function statusValueExistsInColumn($column_id, $value) {
        return StatusSelectValue::where('column_id', $column_id)
                              ->where('value', $value)
                              ->exists();
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'column_id' => 'required|exists:workspace_columns,id', 
            'value' => 'required|string',
        ]);
        
        $defaultValues = StatusSelectValue::whereNull('column_id')->get();
        
        $columnHasValues = StatusSelectValue::where('column_id', $validated['column_id'])->count() > 0;
        
        if (!$columnHasValues && $defaultValues->count() > 0) {
            foreach ($defaultValues as $defaultValue) {
                StatusSelectValue::create([
                    'column_id' => $validated['column_id'],
                    'value' => $defaultValue->value,
                ]);
            }
        }
        
        if (!$this->statusValueExistsInColumn($validated['column_id'], $validated['value'])) {
            StatusSelectValue::create([
                'column_id' => $validated['column_id'],
                'value' => $validated['value'],
            ]);
            return redirect()->back()->with("message", "Status value added successfully.");
        } else {
            return redirect()->back()->with("error", "A status value with this name already exists for this column.");
        }
}

    public function destroy(StatusSelectValue $selectvalue)
    {
        try {
            if (!$selectvalue) {
                return redirect()->back()->with('error', 'Status value not found.');
            }
            
            $selectvalue->delete();
            
            return redirect()->back()->with('message', '');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the status value: ');
        }
    }
}
