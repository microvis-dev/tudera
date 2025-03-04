<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function createUser(Request $request) {
        return inertia('Setup/CreateUser');
    }

    public function createWorkspace(Request $request) {
        return inertia('Setup/CreateWorkspace');
    }
}
