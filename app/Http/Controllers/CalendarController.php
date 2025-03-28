<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Log;

class CalendarController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $workspaces = $user->workspaces;
        
        $workspace_ids = $workspaces->pluck('id')->toArray();
        
        $workspace_events = Calendar::where('user_id', $user->id)
                          ->whereIn('workspace_id', $workspace_ids)
                          ->get();
    

        return inertia('Calendar/Index', [
            "workspace_events" => $workspace_events
        ]);    
    }

    public function create() {
        
    }

    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $validated = $request->validate([
                'workspace_id' => 'required|exists:workspaces,id',
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            
            $workspace = $user->workspaces()->find($validated['workspace_id']);
            if (!$workspace) {
                return redirect()->back()->with('error', 'You do not have access to this workspace.');
            }
            
            Calendar::create([
                'workspace_id' => $validated['workspace_id'],
                'user_id' => $user->id,
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);
            
            return redirect()->back()->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating calendar event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    public function update() {
        
    }

    public function destroy() {
        
    }
}
