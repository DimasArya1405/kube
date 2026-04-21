<?php

namespace App\Http\Controllers;

use App\Models\Kube;
use App\Models\DesaKelurahan;
use App\Models\ClusterUsaha;
use Illuminate\Http\Request;

class KubeController extends Controller
{
    public function index()
    {
        $kubes = Kube::with([
            'desa.kecamatan',
            'clusterUsaha.kategori',
            // Rute baru: Penugasan -> Tabel Pendamping -> Penugasan Koor -> Tabel Koordinator
            'pembagianPendamping.pendamping.pembagianKoordinator.koordinator',
            'pembagianPendamping.pembagianKoordinator.koordinator'
        ])->get();
        
        $desas = DesaKelurahan::orderBy('nama_desa_kelurahan', 'asc')->get();
        $clusters = ClusterUsaha::all();

        return view('admin.data_master.kube', compact('kubes', 'desas', 'clusters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kube' => 'required|string|max:100',
            'id_desa_kelurahan' => 'required|integer',
            'id_cluster' => 'required|integer',
            'keterangan' => 'required|string',
        ]);

        Kube::create([
            'nama_kube' => $request->nama_kube,
            'id_desa_kelurahan' => $request->id_desa_kelurahan,
            'id_cluster' => $request->id_cluster,
            'tanggal_terbentuk' => $request->tanggal_terbentuk,
            'status' => $request->status ?? 'Tidak Aktif',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Data KUBE berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $kube = Kube::where('id_kube', $id)->firstOrFail();
        $kube->delete();

        return redirect()->back()->with('success', 'Data KUBE berhasil dihapus!');
    }

    public function show($id)
    {
        // Ambil data KUBE beserta relasinya, plus data ANGGOTA-nya
        // Pastikan relasi 'anggota' udah lu buat di Model Kube (hasMany AnggotaKube)
        $kube = Kube::with(['desa', 'clusterUsaha', 'anggota'])->where('id_kube', $id)->firstOrFail();

        return view('admin.data_master.detail_kube', compact('kube'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi inputan dari Modal Edit
        $request->validate([
            'nama_kube' => 'required|string|max:100',
            'id_desa_kelurahan' => 'required|integer',
            'id_cluster' => 'required|integer',
            'status' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        // 2. Cari data KUBE yang mau diedit
        $kube = Kube::where('id_kube', $id)->firstOrFail();

        // 3. Simpan perubahan ke database
        $kube->update([
            'nama_kube' => $request->nama_kube,
            'id_desa_kelurahan' => $request->id_desa_kelurahan,
            'id_cluster' => $request->id_cluster,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        // 4. Balik ke halaman semula
        return redirect()->back()->with('success', 'Data KUBE berhasil diupdate!');
    }
}
