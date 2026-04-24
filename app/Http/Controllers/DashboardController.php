<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\DesaKelurahan;
use Illuminate\Support\Facades\Hash;

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
        return view('ketua_kube.dashboard.index');
    }

    public function pendamping()
    {
        return view('pendamping.dashboard.index');
    }

    public function koordinator()
    {
        return view('koordinator.dashboard.index');
    }

    public function tim()
    {
        return view('dashboard.tim');
    }

    public function dinas()
    {
        return view('kepala_dinas.dashboard.index');
    }
}