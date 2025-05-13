<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MessagingService;
use App\Services\WorkspaceService;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use function PHPUnit\Framework\isEmpty;

class AuthController extends Controller
{

    public function index() {
        return inertia('Auth/Auth');
    }

    public function store(Request $request) {
        if (!Auth::attempt($request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]), $request->is_remember ?? false)) {
            throw ValidationException::withMessages([
                'email' => 'Auth failed'
            ]);
        };

        $request->session()->regenerate();
        
        $user = $request->user();
        if ($user->workspaces()->exists()) {
            WorkspaceService::change($user, $user->workspaces()->first());

            //return redirect()->intended('dashboard');
            return redirect()->route("dashboard.index");
        } else {
            return redirect()->route('setup.workspace.create');
        }

    }

    public function check_email(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email'
            ]);

            $emailExists = User::where('email', $request->email)->exists();

            return response()->json(["status" => "success", 'exists' => $emailExists]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e->getMessage()]);
        }
    }

    public function destroy(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.index');
    }
}
