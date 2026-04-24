<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPerkembanganUsaha;
use App\Models\Keuangan;

class DataPerkembanganUsahaController extends Controller
{
    public function index()
    {
        $data = DataPerkembanganUsaha::with('laporan.cluster.kube')->get();
        $laporan = Keuangan::with('cluster.kube')->get();
        
        $kubeList = $laporan->map(function($lap) {
            if (!$lap->cluster) return null;
            $kube = $lap->cluster->kube->first();
            if (!$kube) return null;
            return [
                'id_cluster' => $lap->id_cluster,
                'nama_kube'  => $kube->nama_kube
            ];
        })->filter()->unique('id_cluster')->values();

        return view('admin.monevbimbingan.perkembangan_usaha', compact('data', 'laporan', 'kubeList'));
    }

    public function getPeriodeByKube($id_cluster)
    {
        $periodes = Keuangan::where('id_cluster', $id_cluster)
            ->select('id_laporan', 'periode_bulan', 'periode_tahun')
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->get();

        return response()->json($periodes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required',
        ]);

        $laporan = Keuangan::findOrFail($request->id_laporan);

        DataPerkembanganUsaha::create([
            'id_laporan'          => $request->id_laporan,
            'periode_bulan'       => $laporan->periode_bulan,
            'periode_tahun'       => $laporan->periode_tahun,
            'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja,
            'perkembangan_usaha'  => $request->perkembangan_usaha,
            'hasil_evaluasi'      => $request->hasil_evaluasi,
            'rekomendasi'         => $request->rekomendasi,
            'status_hasil'        => $request->status_hasil,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        DataPerkembanganUsaha::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}