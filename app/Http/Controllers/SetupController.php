<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function createUser(Request $request) {
        return inertia('Setup/CreateUser');
    }



    public function createWorkspace(Request $request) {
        return inertia('Setup/CreateWorkspace');
    }

    public function store_workspace(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $workspace = new Workspace();
        $workspace->name = $request->input('name');
        $workspace->save();

        return redirect()->intended('/')->with('success', 'Workspace created successfully!');
    }
}
