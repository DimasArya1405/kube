<?php

namespace App\Http\Controllers;

use App\Models\Kube;
use Illuminate\Support\Facades\DB;

class KepalaDinasController extends Controller
{
    public function dashboard()
    {
        $totalKube      = Kube::count();
        $kubeAktif      = Kube::where('status', 'Aktif')->count();
        $kubeTidakAktif = Kube::where('status', 'Tidak Aktif')->count();

        $rekapKecamatan = DB::table('kube')
            ->join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan', 'desa_kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->select(
                'kecamatan.nama_kecamatan',
                DB::raw('COUNT(kube.id_kube) as total'),
                DB::raw('SUM(CASE WHEN kube.status = "Aktif" THEN 1 ELSE 0 END) as aktif'),
                DB::raw('SUM(CASE WHEN kube.status = "Tidak Aktif" THEN 1 ELSE 0 END) as tidak_aktif')
            )
            ->groupBy('kecamatan.id_kecamatan', 'kecamatan.nama_kecamatan')
            ->orderByDesc('total')
            ->get();

        $top5Kecamatan   = $rekapKecamatan->take(5);
        $maxTotal        = $rekapKecamatan->max('total') ?: 1;
        $chartLabels     = $rekapKecamatan->pluck('nama_kecamatan');
        $chartTotal      = $rekapKecamatan->pluck('total');
        $chartAktif      = $rekapKecamatan->pluck('aktif');
        $chartTidakAktif = $rekapKecamatan->pluck('tidak_aktif');

        return view('kepala_dinas.dashboard.index', compact(
            'totalKube',
            'kubeAktif',
            'kubeTidakAktif',
            'top5Kecamatan',
            'maxTotal',
            'chartLabels',
            'chartTotal',
            'chartAktif',
            'chartTidakAktif'
        ));
    }
}