<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if (auth()->attempt($credentials)) {
            return redirect('dashboard')->with('success', 'Logged in successfully.');
        }
        return redirect('/login')->with('error', 'Email atau Password salah, coba lagi.');
    }

    public function logout()
    {
        if (!auth()->check()) {
            return redirect('/')->with('error', 'You are not logged in.');
        }

        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
