<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\DesaKelurahan;
use App\Models\Kube;
use App\Models\ClusterUsaha;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Galeri;

class DashboardController extends Controller
{
    public function admin()
    {
        $users = User::all();
        $galeri = Galeri::latest()->take(6)->get();

        return view('admin.dashboard.index', compact('users', 'galeri'));
    }
    
    public function ketua()
    {
        // 1. Cek apakah akun ketua ini sudah punya data di tabel KUBE
        $kube = Kube::with(['desa', 'clusterUsaha', 'anggota'])
            ->where('id_user', Auth::id())
            ->first();

        // 2. Kalau KUBE BELUM ADA (Berarti dia baru pertama kali login)
        if (!$kube) {
            // Ambil data untuk form dropdown
            $desas = DesaKelurahan::orderBy('nama_desa_kelurahan', 'asc')->get();
            $clusters = ClusterUsaha::all();

            // Arahkan ke halaman form pengajuan (Kak Yana harus bikin view ini)
            return view('ketua_kube.manajemen_internal.pengajuan_kube_baru', compact('desas', 'clusters'));
        }

        // 3. Kalau KUBE SUDAH ADA 
        // Arahkan ke halaman dashboard utama dia, dan bawa data $myKube-nya
        return view('ketua_kube.manajemen_internal.detail_kube', compact('kube'));
    }

    public function pendamping()
    {
        return view('pendamping.dashboard.index');
    }

    public function koordinator()
    {
        return view('koordinator.dashboard.index');
    }

    public function kepala_dinas()
    {
        return view('kepala_dinas.dashboard.index');
    }
}
