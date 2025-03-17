<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request) {
        $user = $request->user();
        $worksapces = $user->workspaces;

        return inertia('Index/Index', [
            "user" => $user,
            "workspaces" => $worksapces
        ]);
    }
}
