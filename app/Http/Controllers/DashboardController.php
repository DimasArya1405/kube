<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\DesaKelurahan;
use Illuminate\Support\Facades\Hash;
use App\Models\Galeri;

class DashboardController extends Controller
{
    public function admin()
    {
        $users = User::all();
        $galeri = Galeri::latest()->take(6)->get();

        return view('admin.dashboard.index', compact('users', 'galeri'));
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
        return view('dashboard.ketua');
    }

    public function pendamping()
    {
        return view('dashboard.pendamping');
    }

    public function koordinator()
    {
        return view('dashboard.koordinator');
    }

    public function tim()
    {
        return view('dashboard.tim');
    }

    public function dinas()
    {
        return view('dashboard.dinas');
    }
}