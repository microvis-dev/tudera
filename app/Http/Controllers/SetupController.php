<?php

namespace App\Http\Controllers;

use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    public function create() {
        return inertia('Setup/CreateUser');
    }

    public function createWorkspace() {
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

        return redirect()->route('dashboard.index')->with('success', 'Workspace created successfully!');
    }
}
