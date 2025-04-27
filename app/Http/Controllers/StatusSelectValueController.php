<?php

namespace App\Http\Controllers;

use App\Models\StatusSelectValue;
use Illuminate\Http\Request;
use Exception;

class StatusSelectValueController extends Controller
{
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
        
        $existingColumnValue = StatusSelectValue::where('column_id', $validated['column_id'])
            ->where('value', $validated['value'])
            ->first();
            
        if (!$existingColumnValue) {
            StatusSelectValue::create([
                'column_id' => $validated['column_id'],
                'value' => $validated['value'],
            ]);
        }
        
        $existingDefaultValue = StatusSelectValue::where('value', $validated['value'])
            ->whereNull('column_id')
            ->first();
            
        if (!$existingDefaultValue) {
            StatusSelectValue::create([
                'column_id' => null,
                'value' => $validated['value'],
            ]);
        }
        
        return redirect()->back()->with("message", "");
    }

    public function update(Request $request) {
        
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
            return redirect()->back()->with('error', 'An error occurred while deleting the status value: ' . $e->getMessage());
        }
    }
}
