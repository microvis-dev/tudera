<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $workspaces = $user->workspaces;
        return inertia('Dashboard/Index');
    }
}
