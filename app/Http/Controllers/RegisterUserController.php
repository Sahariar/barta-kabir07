<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterUserController extends Controller
{
    //
    public function create(): View
    {
        return view('register');
    }

    public function store(Request $request)
    {

        $validatedAttributes = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::min(6)],
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Validate image
            'bio' => 'required|string|max:1000'
        ]);
        // Handle the file upload
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $profilePicturePath = $profilePicture->store('profile_pictures', 'public'); // Store in 'storage/app/public/profile_pictures'
        } else {
            $profilePicturePath = null;
        }
        // Create the user and hash the password
        $user = User::create([
            'name' => $validatedAttributes['name'],
            'username' => $validatedAttributes['username'],
            'email' => $validatedAttributes['email'],
            'password' => Hash::make($validatedAttributes['password']),
            'profile_picture' => $profilePicturePath,
            'bio' => $validatedAttributes['bio']
        ]);
        // login
        Auth::login($user);
        // redirect
        return redirect('/profile');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Validate incoming data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'password' => 'nullable|string|min:6',
        ]);

        // Handle file upload for profile picture
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                // Delete the old profile picture if it exists
                Storage::disk('public')->delete($user->profile_picture);
            }

            // Store the new profile picture
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = $profilePicturePath;
        } else {
            // Remove profile_picture from validated data if no new file is uploaded
            unset($validatedData['profile_picture']);
        }

        // Hash password if it's being updated
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            // Remove password from validated data if it's not being updated
            unset($validatedData['password']);
        }
        // dd($user);
        // Update user with the validated data
        $user->update($validatedData);
        // Redirect with success message
        return redirect()->route('profile')->with('success', 'User updated successfully!');
    }
}
