<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        $role = Auth::user()->role;

        if ($role == 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin');
        } elseif ($role == 'ketua_kube') {
            return redirect('/ketua_kube')->with('success', 'Selamat datang Ketua KUBE');
        } elseif ($role == 'pendamping') {
            return redirect('/pendamping')->with('success', 'Selamat datang Pendamping');
        } elseif ($role == 'koordinator') {
            return redirect('/koordinator')->with('success', 'Selamat datang Koordinator');
        } elseif ($role == 'ketua_tim_kube') {
            return redirect('')->with('success', 'Selamat datang Ketua Tim');
        } elseif ($role == 'kepala_dinas') {
            return redirect('/kepala_dinas')->with('success', 'Selamat datang Kepala Dinas');
        }
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