<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Notification;

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

    public function destroy(Request $request) {
        try {
            
        } catch (Exeption $e) {
            Log::error('Error in NotificationController destroy: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred!');
        }
    }
}
