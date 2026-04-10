<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\DesaKelurahan;
use App\Models\Kecamatan;

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
            return redirect('/ketua_kube/dashboard')->with('success', 'Selamat datang Ketua KUBE');
        } elseif ($role == 'pendamping') {
            return redirect('/pendamping/dashboard')->with('success', 'Selamat datang Pendamping');
        } elseif ($role == 'koordinator') {
            return redirect('/koordinator/dashboard')->with('success', 'Selamat datang Koordinator');
        } elseif ($role == 'ketua_tim_kube') {
            return redirect('/ketua_tim_kube/dashboard')->with('success', 'Selamat datang Ketua Tim');
        } elseif ($role == 'kepala_dinas') {
            return redirect('/kepala_dinas/dashboard')->with('success', 'Selamat datang Kepala Dinas');
        }
    }
    return back()->with('error', 'Email atau password salah');
}

    // ================= REGISTER =================
public function showRegister()
{
    $kecamatan = Kecamatan::all();
    $desa = DesaKelurahan::all();
    return view('Auth.register', compact('kecamatan', 'desa'));
}
    
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:100',
            'nik' => 'required|max:30',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3',
            'no_hp' => 'required|max:15',
            'alamat' => 'required',
            'id_kecamatan' => 'required',
            'id_desa_kelurahan' => 'required',
            'role' => 'required'
        ]);

        User::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'id_kecamatan' => $request->id_kecamatan,
            'id_desa_kelurahan' => $request->id_desa_kelurahan,
            'role' => $request->role,
            'status' => 'aktif'
        ]);
        return redirect('/login')->with('success', 'Akun berhasil dibuat!');
    }
    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}