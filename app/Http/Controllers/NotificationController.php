<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Exception;
use Illuminate\Http\Request;
use Log;

class NotificationController extends Controller
{
    public function store(Request $request) {
        try {
            $request->validate([
                'value' => 'required|string|max:65535',
            ]);
            
        } catch (Exception $e) {
            Log::error('Error in NotificationController store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred!');
        }
    }

    public function destroy(Request $request, $notification) {
        try {
            $notification = Notification::findOrFail($notification);
            
            if ($notification->user_id !== $request->user()->id) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
            
            $notification->delete();
            
            return redirect()->back()->with('success', 'Notification deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error in NotificationController destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred!');
        }
    }
}
