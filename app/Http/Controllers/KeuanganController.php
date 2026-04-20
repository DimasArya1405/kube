<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Models\ClusterUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class KeuanganController extends Controller
{
    public function index() 
    {
        $laporan = Keuangan::with(['cluster'])
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->get();

        $kubeDisetujui = DB::table('pengajuan_kube')
            ->join('kube', 'pengajuan_kube.id_kube', '=', 'kube.id_kube')
            ->where('pengajuan_kube.status_pengajuan', 'disetujui')
            ->select('pengajuan_kube.id_pengajuan_kube', 'kube.nama_kube as nama_tampilan')
            ->get();

        $clusters = ClusterUsaha::all();
        $totalOmset = $laporan->sum('omset_pendapatan');
        $totalLaba = $laporan->sum('laba_bersih');

$duaDataTerakhir = Keuangan::select('periode_bulan', 'periode_tahun')
    ->groupBy('periode_tahun', 'periode_bulan')
    ->orderBy('periode_tahun', 'desc')
    ->orderBy('periode_bulan', 'desc')
    ->limit(2)
    ->get();

if ($duaDataTerakhir->count() >= 2) {
    $dt1 = $duaDataTerakhir[0];
    $labaSekarang = Keuangan::where('periode_bulan', $dt1->periode_bulan)
        ->where('periode_tahun', $dt1->periode_tahun)->sum('laba_bersih');
    $dt2 = $duaDataTerakhir[1];
    $labaLalu = Keuangan::where('periode_bulan', $dt2->periode_bulan)
        ->where('periode_tahun', $dt2->periode_tahun)->sum('laba_bersih');

    if ($labaLalu > 0) {
        $p = (($labaSekarang - $labaLalu) / $labaLalu) * 100;
        $perkembangan = ($p >= 0 ? '+ ' : '- ') . number_format(abs($p), 1) . '%';
    } else {
        $perkembangan = "+ 100%";
    }
} else {
    $perkembangan = "Belum ada Data";
}
        return view('admin.monevbimbingan.laporan_keuangan', compact(
            'laporan', 'clusters', 'kubeDisetujui', 
            'totalOmset', 'totalLaba', 'perkembangan'
        ));
    }

    public function store(Request $request) 
    {
        $request->validate([
            'id_persetujuan' => 'required',
            'id_cluster' => 'required',
            'omset_pendapatan' => 'required|numeric|min:0',
            'total_pengeluaran' => 'required|numeric|min:0',
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer',
            'tanggal_laporan' => 'required|date'
        ]);

        $laba = $request->omset_pendapatan - $request->total_pengeluaran;
        $lalu = Keuangan::where('id_persetujuan', $request->id_persetujuan)
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->first();

        $progres = 'Tetap';
        if ($lalu) {
            if ($laba > $lalu->laba_bersih) $progres = 'Meningkat';
            elseif ($laba < $lalu->laba_bersih) $progres = 'Menurun';
        }

        $data = $request->all();
        $data['laba_bersih'] = $laba;
        $data['total_omset'] = $request->omset_pendapatan; 
        $data['progres_keuangan'] = $progres;
        $data['status_validasi'] = 'Draft';

        if ($request->hasFile('lampiran_keuangan')) {
            $file = $request->file('lampiran_keuangan');
            $namaFile = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/keuangan'), $namaFile);
            $data['lampiran_keuangan'] = $namaFile;
        }

        Keuangan::create($data);
        return redirect()->back()->with('success', 'Laporan berhasil disimpan!');
    }

    public function update(Request $request, $id) 
    {
        $laporan = Keuangan::findOrFail($id);
        
        $laba = $request->omset_pendapatan - $request->total_pengeluaran;
        $lalu = Keuangan::where('id_persetujuan', $request->id_persetujuan)
            ->where('id_laporan', '!=', $id)
            ->where(function($q) use ($laporan) {
                $q->where('periode_tahun', '<', $laporan->periode_tahun)
                  ->orWhere(function($sq) use ($laporan) {
                      $sq->where('periode_tahun', $laporan->periode_tahun)
                         ->where('periode_bulan', '<', $laporan->periode_bulan);
                  });
            })
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->first();

        $progres = 'Tetap';
        if ($lalu) {
            if ($laba > $lalu->laba_bersih) $progres = 'Meningkat';
            elseif ($laba < $lalu->laba_bersih) $progres = 'Menurun';
        }

        $updateData = [
            'id_persetujuan' => $request->id_persetujuan,
            'id_cluster' => $request->id_cluster,
            'omset_pendapatan' => $request->omset_pendapatan,
            'total_omset' => $request->omset_pendapatan,
            'total_pengeluaran' => $request->total_pengeluaran,
            'laba_bersih' => $laba,
            'progres_keuangan' => $progres,
            'keterangan' => $request->keterangan,
            'tanggal_laporan' => $request->tanggal_laporan,
            'periode_bulan' => $request->periode_bulan,
            'periode_tahun' => $request->periode_tahun,
        ];

       if ($request->hasFile('lampiran_keuangan')) {
        if ($laporan->lampiran_keuangan && file_exists(public_path('uploads/keuangan/'.$laporan->lampiran_keuangan))) {
            unlink(public_path('uploads/keuangan/'.$laporan->lampiran_keuangan));
        }

        $file = $request->file('lampiran_keuangan');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/keuangan'), $filename);

        $laporan->lampiran_keuangan = $filename;
        $laporan->save();
    }


        $laporan->update($updateData);
        return redirect()->back()->with('success', 'Laporan diperbarui!');
    }

    public function destroy($id) 
    {
        $l = Keuangan::findOrFail($id);
        if($l->lampiran_keuangan && File::exists(public_path('uploads/keuangan/'.$l->lampiran_keuangan))) {
            File::delete(public_path('uploads/keuangan/'.$l->lampiran_keuangan));
        }
        
        $l->delete();
        return redirect()->back()->with('success', 'Data laporan berhasil dihapus!');
    }
}