<?php

namespace App\Http\Controllers;

use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use Exception;
use Illuminate\Http\Request;
use Log;

class WorkspaceController extends Controller
{
    function index(Request $request) {
        try {
            $user = $request->user();
            $user_workspaces = $user->workspaces()->pluck('name')->toArray();

            return inertia('Workspaces/Index', [
                'user_workspaces' => $user_workspaces
            ]);
        } catch (Exception $e) {
            Log::error('Hiba WorkspaceController: ' . $e->getMessage());
            return redirect()->intended('/')->with('error', 'An error occurred while retrieving your workspaces. Please try again later.');
        }
        
    }
}
