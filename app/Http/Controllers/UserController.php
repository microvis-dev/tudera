<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|max:255',
            'phone' => 'string',
            'profileImageFile' => 'nullable|image|mimes:png,jpg|max:2048',
        ]);

        $redirectTo = $request->input('redirectTo', 'setup.workspace.create');

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::make($validator->validated());
        if ($user->save()) {
            if ($request->hasFile('profileImageFile')) {
                $user->uploadProfileImage($request->file('profileImageFile'));
            }
            Auth::login($user);
            return redirect()->route($redirectTo);
        } else {
            return back()
                ->withErrors(['error' => 'Failed to create user'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'nullable|string',
                'old_password' => 'required_with:password',
                'password' => 'nullable|min:8|max:255|confirmed',
            ]);

            if (!empty($validated['password'])) {
                if (!Auth::guard('web')->validate([
                    'email' => $user->email,
                    'password' => $request->old_password,
                ])) {
                    return back()->withErrors(['old_password' => 'The provided password is incorrect.']);
                }
                
                $user->password = bcrypt($validated['password']);
            }

            $user->name = $validated['name'];
            $user->phone_number = $validated['phone_number'];
            
            $user->save();
            
            if (!empty($validated['password'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('auth.index');
            }

            return redirect()->route('settings.index')->with('success', 'Profile updated successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->withErrors(['error' => 'Failed to update profile.'])->withInput();
        }
    }

    public function destroy(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'password' => 'required|string'
            ]);

            if (!Auth::guard('web')->validate([
                'email' => $user->email,
                'password' => $validated['password'],
            ])) {
                return back()->withErrors(['password' => 'The provided password is incorrect.']);
            }

            $user->delete();
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('auth.index')->with('success', 'Your account has been deleted successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete account.']);
        }
    }
}
