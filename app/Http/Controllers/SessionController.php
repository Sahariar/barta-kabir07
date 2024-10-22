<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionController extends Controller
{
    //create
    public function create()
    {
        return view('login');
    }
    //store
    public function store(Request $request)
    {
        $validateAttributes = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // login
        if (! Auth::attempt($validateAttributes)) {
            throw ValidationException::withMessages([
                'email' => 'Sorry, those credentials do not match'
            ]);
        }

        // regenrate the Session token
        request()->session()->regenerate();
        // redirect
        return redirect('/profile');
    }

    public function destroy()
    {
        Auth::logout();
        return redirect('/');
    }
}
