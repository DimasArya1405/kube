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

class DashboardController extends Controller
{
    public function admin()
    {
        $users = User::all();
        return view('admin.dashboard.index', compact('users'));
    }
    public function users()
    {
        $users = User::paginate(10);
        $kecamatan = Kecamatan::all();
        $desa = DesaKelurahan::all();
        return view('admin.data_master.users', compact('users', 'kecamatan', 'desa'));
    }

    public function store(Request $request)
    {
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
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }
    // EDIT (ambil data)
    public function edit($id)
    {
        $user = User::with(['kecamatan', 'desa'])->findOrFail($id);
        return response()->json($user);
    }
    // UPDATE
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'id_kecamatan' => $request->id_kecamatan,
            'id_desa_kelurahan' => $request->id_desa_kelurahan,
            'role' => $request->role,
            'status' => $request->status,
        ]);
        return back()->with('success', 'User berhasil diupdate');
    }
    // DELETE
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'User berhasil dihapus');
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
