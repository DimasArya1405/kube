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

        // Filter Tahun & Bulan (optional)
        if (!empty($tahun)) {
            $query->whereYear('created_at', $tahun);
        }

        if (!empty($bulan)) {
            $query->whereMonth('created_at', $bulan);
        }

        // Filter Kecamatan
        if (!empty($id_kecamatan)) {
            $query->whereHas('desa', function ($q) use ($id_kecamatan) {
                $q->where('id_kecamatan', $id_kecamatan);
            });
        }

        $dataKube = $query->get();

        // GROUPING PER KECAMATAN
        $grouped = $dataKube->groupBy(function ($item) {
            return $item->desa->id_kecamatan ?? 0;
        });

        $rekap = $grouped->map(function ($items, $id_kecamatan) {
            $first = $items->first();

            return [
                'id_kecamatan' => $id_kecamatan,
                'nama_kecamatan' => $first->desa->kecamatan->nama_kecamatan ?? 'Tidak Diketahui',
                'jumlah_kube' => $items->count(),
                'kube_aktif' => $items->where('status', 'Aktif')->count(),
                'kube_tidak_aktif' => $items->where('status', 'Tidak Aktif')->count(),
            ];
        })->values();

        // Dropdown kecamatan
        $kecamatanList = Kecamatan::orderBy('nama_kecamatan', 'asc')->get();

        // TOTAL
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

    //  DETAIL PER KECAMATAN
    public function detail($id_kecamatan)
    {
        $data = Kube::with('desa.kecamatan')
            ->whereHas('desa', function ($q) use ($id_kecamatan) {
                $q->where('id_kecamatan', $id_kecamatan);
            })
            ->get();

        // Ambil nama kecamatan
        $namaKecamatan = optional($data->first()?->desa?->kecamatan)->nama_kecamatan ?? 'Tidak Diketahui';

        return view('admin.rekap_kube.detail', compact('data', 'namaKecamatan'));
    }
}