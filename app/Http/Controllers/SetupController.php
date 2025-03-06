<?php

namespace App\Http\Controllers;

use App\Models\UsersToWorkspace;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    public function createUser(Request $request) {
        return inertia('Setup/CreateUser');
    }

    public function createWorkspace(Request $request) {
        return inertia('Setup/CreateWorkspace');
    }
}
