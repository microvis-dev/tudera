<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'pictureImage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = \Auth::user();

        if ($request->hasFile('pictureImage')) {
            $user->uploadProfileImage($request->file('pictureImage'));
        }

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
}
