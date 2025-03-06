<?php

namespace App\Http\Controllers;

use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    public function createUser(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        if (!$validated) {
            return redirect()->route('auth');
        }

        if ($request->isMethod('post')) {
            if (isset($request->register)) {
                return redirect()->route('user.store', ['redirect' => 'setup.workspace.create']);
            }
            return inertia('Setup/CreateUser', [
                'email' => $validated['email'],
            ]);
        }
        return redirect()->route('auth');
    }

    public function createWorkspace(Request $request) {
        return inertia('Setup/CreateWorkspace');
    }

    public function store_workspace(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);


        DB::transaction(function () use ($request) {
            $workspace = new Workspace();
            $workspace->name = $request->input('name');
            $workspace->save();

            $users_to_workspace = new UsersToWorkspace();
            $users_to_workspace->workspace_id = $workspace->id;
            $users_to_workspace->user_id = $request->user()->id;
            $users_to_workspace->save();
        });

        return redirect()->back()->with('success', 'Workspace created successfully!');
        // return redirect()->intended('/')->with('success', 'Workspace created successfully!');
    }
}
