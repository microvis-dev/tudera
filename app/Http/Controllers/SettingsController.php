<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index() {
        return inertia('Settings/Index');
    }
}
