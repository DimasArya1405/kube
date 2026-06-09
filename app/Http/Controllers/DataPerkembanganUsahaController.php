<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPerkembanganUsaha;
use App\Models\Keuangan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerkembanganUsahaExport;
use Barryvdh\DomPDF\Facade\Pdf;

class DataPerkembanganUsahaController extends Controller
{
    public function index(Request $request)
{
    $query = DataPerkembanganUsaha::with('laporan.cluster.kube');

    // Filter status
    if ($request->status) {
        $query->where('status_hasil', $request->status);
    }

    // Filter perkembangan
    if ($request->perkembangan) {
        $query->where('perkembangan_usaha', $request->perkembangan);
    }

    // Filter KUBE (lewat relasi laporan)
    if ($request->id_cluster) {
        $query->whereHas('laporan', function($q) use ($request) {
            $q->where('id_cluster', $request->id_cluster);
        });
    }

    // Search
    if ($request->search) {
        $search = $request->search;
        $query->whereHas('laporan.cluster.kube', function($q) use ($search) {
            $q->where('nama_kube', 'like', '%' . $search . '%');
        });
    }

    $data = $query->get();
    $laporan = Keuangan::with('cluster.kube')->get();
    
    $kubeList = $laporan->map(function($lap) {
        if (!$lap->cluster) return null;
        $kube = $lap->cluster->kube->first();
        if (!$kube) return null;
        return [
            'id_cluster' => $lap->id_cluster,
            'nama_kube'  => $kube->nama_kube
        ];
    })->filter()->values();

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

    public function show($id)
    {
        $item = DataPerkembanganUsaha::with('laporan.cluster.kube')->findOrFail($id);

        $namaKube = '-';
        if ($item->laporan && $item->laporan->cluster) {
            $kube = $item->laporan->cluster->kube->first();
            if ($kube) $namaKube = $kube->nama_kube;
        }

        return response()->json([
            'nama_kube'           => $namaKube,
            'periode'             => ($item->laporan->periode_bulan ?? '-') . '/' . ($item->laporan->periode_tahun ?? '-'),
            'omset'               => number_format($item->omset_pendapatan ?? 0, 0, ',', '.'),
            'total_pengeluaran'   => number_format($item->total_pengeluaran ?? 0, 0, ',', '.'),
            'laba_bersih'         => number_format($item->laba_bersih ?? 0, 0, ',', '.'),
            'total_omset'         => number_format($item->total_omset ?? 0, 0, ',', '.'),
            'jumlah_tenaga_kerja' => $item->jumlah_tenaga_kerja ?? '-',
            'perkembangan_usaha'  => $item->perkembangan_usaha,
            'tingkat_kemandirian' => $item->tingkat_kemandirian ?? '-',
            'status_hasil'        => $item->status_hasil,
            'hasil_evaluasi'      => $item->hasil_evaluasi ?? '-',
            'rekomendasi'         => $item->rekomendasi ?? '-',
            'created_at'          => $item->created_at->format('d M Y H:i'),
        ]);
    }

    public function edit($id)
    {
        $item = DataPerkembanganUsaha::with('laporan')->findOrFail($id);
        return response()->json($item);
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
            'omset_pendapatan'    => $laporan->omset_pendapatan,
            'total_pengeluaran'   => $laporan->total_pengeluaran,
            'laba_bersih'         => $laporan->laba_bersih,
            'total_omset'         => $laporan->total_omset,
            'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja,
            'perkembangan_usaha'  => $request->perkembangan_usaha,
            'hasil_evaluasi'      => $request->hasil_evaluasi,
            'rekomendasi'         => $request->rekomendasi,
            'status_hasil'        => $request->status_hasil,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_laporan' => 'required',
        ]);

        $laporan = Keuangan::findOrFail($request->id_laporan);
        $item = DataPerkembanganUsaha::findOrFail($id);

        $item->update([
            'id_laporan'          => $request->id_laporan,
            'periode_bulan'       => $laporan->periode_bulan,
            'periode_tahun'       => $laporan->periode_tahun,
            'omset_pendapatan'    => $laporan->omset_pendapatan,
            'total_pengeluaran'   => $laporan->total_pengeluaran,
            'laba_bersih'         => $laporan->laba_bersih,
            'total_omset'         => $laporan->total_omset,
            'jumlah_tenaga_kerja' => $request->jumlah_tenaga_kerja,
            'perkembangan_usaha'  => $request->perkembangan_usaha,
            'hasil_evaluasi'      => $request->hasil_evaluasi,
            'rekomendasi'         => $request->rekomendasi,
            'status_hasil'        => $request->status_hasil,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    public function getGrafikData()
    {
        $data = Keuangan::with('cluster.kube')
            ->orderBy('periode_tahun', 'asc')
            ->orderBy('periode_bulan', 'asc')
            ->get();

        $result = $data->map(function($lap) {
            $namaKube = '-';
            if ($lap->cluster) {
                $kube = $lap->cluster->kube->first();
                if ($kube) $namaKube = $kube->nama_kube;
            }
            return [
                'label' => $namaKube . ' ' . $lap->periode_bulan . '/' . $lap->periode_tahun,
                'omset' => (float) $lap->omset_pendapatan,
            ];
        });

        return response()->json($result);
    }

    public function destroy($id)
    {
        DataPerkembanganUsaha::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
    public function exportExcel()
{
    return Excel::download(new PerkembanganUsahaExport, 'perkembangan-usaha.xlsx');
}

public function exportPdf()
{
    $data = DataPerkembanganUsaha::with('laporan.cluster.kube')->get();
    $pdf = Pdf::loadView('admin.monevbimbingan.perkembangan_usaha_pdf', compact('data'))
              ->setPaper('a4', 'landscape');
    return $pdf->download('perkembangan-usaha.pdf');
}
}