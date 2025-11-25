<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login
    public function doLogin(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $remember = $request->boolean('remember'); // checkbox "ingat saya"

        // Coba login
        if (Auth::attempt($credentials, $remember)) {
            // demi keamanan: ganti session id
            $request->session()->regenerate();

            // arahkan ke halaman yang diminta, kalau nggak ada ke dashboard
            return redirect()->intended(route('dashboard'));
        }

        // kalau gagal
        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->withInput([
            'email'    => $request->email,
            'remember' => $request->remember,
        ]);
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();                     // keluarkan user
        $request->session()->invalidate();  // invalidkan session lama
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
