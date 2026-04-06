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
        $kubes = Kube::with(['desa', 'clusterUsaha'])->get();
        
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
            'status' => $request->status ?? 'Aktif',
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
}