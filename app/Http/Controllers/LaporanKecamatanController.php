<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kube;
use App\Models\Kecamatan;
use App\Models\ClusterUsaha;

class LaporanKecamatanController extends Controller
{
    // =======================
    // DETAIL DATA KUBE
    // =======================
    public function detail($id)
    {
        $data = DB::table('kube')
            ->join('desa_kelurahan','kube.id_desa_kelurahan','=','desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan','desa_kelurahan.id_kecamatan','=','kecamatan.id_kecamatan')
            ->join('cluster_usaha','kube.id_cluster','=','cluster_usaha.id_cluster')

            // RELASI FIX
            ->leftJoin('pengajuan_kube','kube.id_kube','=','pengajuan_kube.id_kube')
            ->leftJoin('laporan_keuangan','pengajuan_kube.id_pengajuan_kube','=','laporan_keuangan.id_persetujuan')

            ->where('kube.id_kube', $id)

            ->select(
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                DB::raw('COALESCE(SUM(laporan_keuangan.total_omset),0) as total_omset'),
                DB::raw('COALESCE(SUM(laporan_keuangan.laba_bersih),0) as laba_bersih'),
                'kube.status'
            )

            ->groupBy(
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                'kube.status'
            )

            ->first();

        return view('admin.laporan.detail_kecamatan', compact('data'));
    }


    // =======================
    // HALAMAN UTAMA
    // =======================
    public function index(Request $request)
    {
        // 🔥 DROPDOWN TAHUN (FIX: fallback ke created_at)
        $tahun = Kube::selectRaw('YEAR(COALESCE(tanggal_terbentuk, created_at)) as tahun')
            ->whereNotNull(DB::raw('COALESCE(tanggal_terbentuk, created_at)'))
            ->distinct()
            ->orderBy('tahun','desc')
            ->get();

        $kecamatan = Kecamatan::all();
        $cluster = ClusterUsaha::all();

        // =======================
        // QUERY UTAMA
        // =======================
        $query = DB::table('kube')
            ->join('desa_kelurahan','kube.id_desa_kelurahan','=','desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan','desa_kelurahan.id_kecamatan','=','kecamatan.id_kecamatan')
            ->join('cluster_usaha','kube.id_cluster','=','cluster_usaha.id_cluster')

            // RELASI FIX
            ->leftJoin('pengajuan_kube','kube.id_kube','=','pengajuan_kube.id_kube')
            ->leftJoin('laporan_keuangan','pengajuan_kube.id_pengajuan_kube','=','laporan_keuangan.id_persetujuan');

        // =======================
        // FILTER
        // =======================
        if($request->tahun && $request->tahun != 'all'){
            $query->whereYear(
                DB::raw('COALESCE(kube.tanggal_terbentuk, kube.created_at)'),
                $request->tahun
            );
        }

        if($request->kecamatan && $request->kecamatan != 'all'){
            $query->where('kecamatan.id_kecamatan', $request->kecamatan);
        }

        if($request->cluster && $request->cluster != 'all'){
            $query->where('cluster_usaha.id_cluster', $request->cluster);
        }

        // hanya yang disetujui
        $query->where('pengajuan_kube.status_pengajuan', 'disetujui');

        // =======================
        // AMBIL DATA
        // =======================
        $data = $query->select(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                DB::raw('COALESCE(SUM(laporan_keuangan.total_omset),0) as total_omset'),
                DB::raw('COALESCE(SUM(laporan_keuangan.laba_bersih),0) as laba_bersih'),
                'kube.status'
            )
            ->groupBy(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                'kube.status'
            )
            ->get();

        // =======================
        // STATISTIK
        // =======================
        $totalKube = $data->count();
        $kubeAktif = $data->where('status','aktif')->count();
        $kubeNonaktif = $data->where('status','!=','aktif')->count();
        $totalOmset = $data->sum('total_omset');
        $totalLaba = $data->sum('laba_bersih');

        return view('admin.laporan.kecamatan', compact(
            'tahun',
            'kecamatan',
            'cluster',
            'data',
            'totalKube',
            'kubeAktif',
            'kubeNonaktif',
            'totalOmset',
            'totalLaba'
        ));
    }
}