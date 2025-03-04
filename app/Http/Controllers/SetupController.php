<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

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
}
