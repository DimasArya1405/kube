<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Kecamatan;
use App\Models\DesaKelurahan;

class AuthController extends Controller
{
    // ================= LOGIN =================
    public function showLogin()
    {
        return view('auth.login');
    }

 public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        // Format role jadi lebih readable
        $role = str_replace('_', ' ', $user->role);
        $role = ucwords($role);

        return redirect('/dashboard')->with('success', 'Selamat datang ' . $role);
    }

    return back()->with('error', 'Email atau password salah');
}

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}