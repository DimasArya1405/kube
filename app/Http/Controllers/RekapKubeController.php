<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kube;
use App\Models\Kecamatan;

class RekapKubeController extends Controller
{
    public function index(Request $request)
{
    $tahun = $request->tahun;
    $bulan = $request->bulan;
    $id_kecamatan = $request->id_kecamatan;

    // Load relasi desa dan kecamatan
    $query = Kube::with('desa.kecamatan');

    // Filter Tahun & Bulan
    if (!empty($tahun)) {
        $query->whereYear('created_at', $tahun);
    }
    if (!empty($bulan)) {
        $query->whereMonth('created_at', $bulan);
    }

    // Filter Kecamatan (lewat tabel desa)
    if (!empty($id_kecamatan)) {
        $query->whereHas('desa', function($q) use ($id_kecamatan) {
            $q->where('id_kecamatan', $id_kecamatan);
        });
    }

    $dataKube = $query->get();

    /** * PERBAIKAN LOGIKA GROUPING
     * Karena id_kecamatan ada di dalam relasi desa, kita group berdasarkan itu
     */
    $grouped = $dataKube->groupBy(function($item) {
        return $item->desa->id_kecamatan ?? 0;
    });

    $rekap = $grouped->map(function ($items) {
        $first = $items->first();

        return [
            // Pastikan mengambil nama dari relasi desa->kecamatan
            'nama_kecamatan' => $first->desa->kecamatan->nama_kecamatan ?? 'Tidak Diketahui',
            'jumlah_kube' => $items->count(),
            'kube_aktif' => $items->where('status', 'Aktif')->count(),
            'kube_tidak_aktif' => $items->where('status', 'Tidak Aktif')->count(),
        ];
    })->values();

    // List untuk dropdown filter
    $kecamatanList = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();

    /**
     * TOTALAN INI OTOMATIS BERUBAH
     * Karena $rekap isinya hanya data yang lolos filter di atas
     */
    $totalSemuaKube = $rekap->sum('jumlah_kube');
    $totalSemuaAktif = $rekap->sum('kube_aktif');
    $totalSemuaTidakAktif = $rekap->sum('kube_tidak_aktif');

    return view('admin.rekap_kube.index', compact(
        'rekap',
        'kecamatanList',
        'tahun',
        'bulan',
        'id_kecamatan',
        'totalSemuaKube',
        'totalSemuaAktif',
        'totalSemuaTidakAktif'
    ));
}
}