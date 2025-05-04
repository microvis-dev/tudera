<?php

namespace App\Http\Controllers;

use App\Models\User;
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

        return redirect()->intended('dashboard'); 
    }

    public function check_email(Request $request) {
        try {
            $request->validate([
                'email' => 'required|string|email'
            ]);
    
            $emailExists = User::where('email', $request->email)->exists();
            if(!$emailExists){
                return response()->json(["status" => "error", "message" => "The email address does not exist."]);
            }
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
