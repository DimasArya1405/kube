<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKube;
use App\Models\Kube; 
use Illuminate\Http\Request;

class AnggotaKubeController extends Controller
{
    public function index()
    {
        $anggotas = AnggotaKube::with('kube')->get();
        
        $kubes = Kube::orderBy('nama_kube', 'asc')->get();

        return view('admin.data_master.anggota_kube', compact('anggotas', 'kubes'));
    }

    public function store(Request $request)
    {
        // Validasi inputan dari form Modal
        $request->validate([
            'id_kube' => 'required|integer',
            'nik' => 'required|string|max:16',
            'nama_anggota' => 'required|string|max:100',
            'jabatan' => 'required|string|max:20',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        // Simpan ke database
        AnggotaKube::create([
            'id_kube' => $request->id_kube,
            'nik' => $request->nik,
            'nama_anggota' => $request->nama_anggota,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Data Anggota berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $anggota = AnggotaKube::where('id_anggota', $id)->firstOrFail();
        $anggota->delete();

        return redirect()->back()->with('success', 'Data Anggota berhasil dihapus!');
    }
}