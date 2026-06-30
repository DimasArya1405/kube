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

    // ================= LOGIN =================
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // ================= CEK STATUS NONAKTIF =================
            if ($user->status == 'nonaktif') {
                Auth::logout(); 
            return back()->with('error', 'Akun Anda belum diaktifkan. Silakan hubungi admin melalui 
            <a href="https://wa.me/628123456789" target="_blank" class="text-green-600 hover:text-green-800 font-bold underline inline-flex items-center gap-1">WhatsApp<i data-lucide="external-link" class="w-3.5 h-3.5 inline"></i></a>');            
            }
            // =======================================================
            $role = $user->role;
            if ($role == 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang Admin');
            } elseif ($role == 'ketua_kube') {
                return redirect('/ketua_kube/dashboard')->with('success', 'Selamat datang Ketua KUBE');
            } elseif ($role == 'pendamping') {
                return redirect('/pendamping/dashboard')->with('success', 'Selamat datang Pendamping');
            } elseif ($role == 'koordinator') {
                return redirect('/koordinator/dashboard')->with('success', 'Selamat datang Koordinator');
            } elseif ($role == 'kepala_dinas') {
                return redirect('/kepala_dinas/dashboard')->with('success', 'Selamat datang Kepala Dinas');
            }
        }
        return back()->with('error', 'Email dan password salah');
    }

    // ================= REGISTER =================
// ================= REGISTER KETUA KUBE =================
    public function showRegisterKetua()
    {
        $kecamatan = Kecamatan::all();
        $desa = DesaKelurahan::all();
        return view('auth.register_ketua', compact('kecamatan', 'desa'));
    }
    
    public function registerKetua(Request $request)
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
            'role' => 'ketua_kube', // Otomatis diset ketua_kube
            'status' => 'nonaktif'
        ]);

        return redirect('/login')->with('success', 'Akun Ketua KUBE berhasil dibuat, tunggu Admin aktivasi!');
    }

    // ================= REGISTER PENDAMPING =================
    public function showRegisterPendamping()
    {
        $kecamatan = Kecamatan::all();
        $desa = DesaKelurahan::all();
        return view('auth.register_pendamping', compact('kecamatan', 'desa'));
    }
    
    public function registerPendamping(Request $request)
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
            'role' => 'pendamping', // Otomatis diset pendamping
            'status' => 'nonaktif'
        ]);

        return redirect('/login')->with('success', 'Akun Pendamping berhasil dibuat, tunggu Admin aktivasi!');
    }

    // ================= REGISTER KOORDINATOR =================
    public function showRegisterKoordinator()
    {
        $kecamatan = Kecamatan::all();
        $desa = DesaKelurahan::all();
        return view('auth.register_koordinator', compact('kecamatan', 'desa'));
    }
    
    public function registerKoordinator(Request $request)
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
            'role' => 'koordinator', // Otomatis diset koordinator
            'status' => 'nonaktif'
        ]);

        return redirect('/login')->with('success', 'Akun Koordinator berhasil dibuat, tunggu Admin aktivasi!');
    }
    
    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}