<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use Log;
use Exception;

class CalendarController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $workspaces = $user->workspaces;
        
        $workspace_ids = $workspaces->pluck('id')->toArray();
        
        $workspace_events = Calendar::whereIn('workspace_id', $workspace_ids)->get();
    

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
            $workspace_id = $request->workspace['id'];

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);
            
            $workspace = $user->workspaces->find($workspace_id);
            if (!$workspace) {
                return redirect()->back()->with('error', 'You do not have access to this workspace.');
            }
            
            Calendar::create([
                'workspace_id' => $workspace_id,
                'user_id' => $user->id,
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);
            
            return redirect()->back()->with('success', 'Event created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating calendar event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }


    public function update(Request $request, $calendar) {
        try {
            $user = $request->user();
            $workspace_id = $request->workspace;

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);
            //dd($validated['start_date']);
            
            $workspace = $user->workspaces->find($workspace_id);
            if (!$workspace) {
                return redirect()->back()->with('error', 'You do not have access to this workspace.');
            }
            
            $calendarEvent = Calendar::where('id', $calendar);

            if (!$calendarEvent) {
                return redirect()->back()->with('error', 'Event not found or you do not have access to it.');
            }

            $calendarEvent->update([
                'title' => $validated['title'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
            ]);
            
            return redirect()->back()->with('success', 'Event updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating calendar event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update event: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $calendar) {
        try {
            $user = $request->user();
            
            $calendarEvent = Calendar::find($calendar);
            
            if (!$calendarEvent) {
                return redirect()->back()->with('error', 'Event not found.');
            }
            
            $workspace = $user->workspaces->find($calendarEvent->workspace_id);
            if (!$workspace) {
                return redirect()->back()->with('error', 'You do not have access to delete this event.');
            }
            
            $calendarEvent->delete();
            
            return redirect()->back()->with('success', 'Event deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting calendar event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }
}
