<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPerkembanganUsaha;
use App\Models\Keuangan;

class DataPerkembanganUsahaController extends Controller
{
    public function index()
    {
        $data = DataPerkembanganUsaha::with('laporan.cluster')->get();
        $laporan = Keuangan::with('cluster')->get();

        return view('admin.monevbimbingan.perkembangan_usaha', compact('data', 'laporan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required',
        ]);

        DataPerkembanganUsaha::create([
            'id_laporan' => $request->id_laporan,
            'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja,
            'perkembangan_usaha' => $request->perkembangan_usaha,
            'hasil_evaluasi' => $request->hasil_evaluasi,
            'rekomendasi' => $request->rekomendasi,
            'status_hasil' => $request->status_hasil,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        DataPerkembanganUsaha::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}