<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kube;
use App\Models\Kecamatan;
use App\Models\KategoriKube;
use App\Exports\RekapKubeExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapKubeController extends Controller
{
    public function index(Request $request)
    {
        $tahun        = $request->tahun;
        $bulan        = $request->bulan;
        $id_kecamatan = $request->id_kecamatan;
        $id_kategori  = $request->id_kategori;

        $query = Kube::with('desa.kecamatan', 'clusterUsaha.kategori');

        if (!empty($tahun))  $query->whereYear('created_at', $tahun);
        if (!empty($bulan))  $query->whereMonth('created_at', $bulan);

        if (!empty($id_kecamatan)) {
            $query->whereHas('desa', fn($q) => $q->where('id_kecamatan', $id_kecamatan));
        }

        if (!empty($id_kategori)) {
            $query->whereHas('clusterUsaha', fn($q) => $q->where('id_kategori', $id_kategori));
        }

        $dataKube = $query->get();

        $grouped = $dataKube->groupBy(fn($item) => $item->desa->id_kecamatan ?? 0);

        $rekap = $grouped->map(function ($items, $id_kecamatan) {
            $first = $items->first();
            return [
                'id_kecamatan'     => $id_kecamatan,
                'nama_kecamatan'   => $first->desa->kecamatan->nama_kecamatan ?? 'Tidak Diketahui',
                'jumlah_kube'      => $items->count(),
                'kube_aktif'       => $items->where('status', 'Aktif')->count(),
                'kube_tidak_aktif' => $items->where('status', 'Tidak Aktif')->count(),
            ];
        })->values();

        $kecamatanList = Kecamatan::orderBy('nama_kecamatan')->get();
        $kategoriList  = KategoriKube::orderBy('nama_kategori')->get();

        $totalSemuaKube       = $rekap->sum('jumlah_kube');
        $totalSemuaAktif      = $rekap->sum('kube_aktif');
        $totalSemuaTidakAktif = $rekap->sum('kube_tidak_aktif');

        return view('admin.rekap_kube.index', compact(
            'rekap', 'kecamatanList', 'kategoriList',
            'tahun', 'bulan', 'id_kecamatan', 'id_kategori',
            'totalSemuaKube', 'totalSemuaAktif', 'totalSemuaTidakAktif'
        ));
    }

    public function exportPdf(Request $request)
    {
        $id_kecamatan = $request->id_kecamatan;
        $id_kategori  = $request->id_kategori;

        $query = DB::table('kube')
            ->join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan', 'desa_kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->join('cluster_usaha', 'kube.id_cluster', '=', 'cluster_usaha.id_cluster')
            ->join('kategori', 'cluster_usaha.id_kategori', '=', 'kategori.id_kategori')
            ->select(
                'kecamatan.nama_kecamatan',
                'kategori.nama_kategori',
                DB::raw('COUNT(kube.id_kube) as jumlah_kube'),
                DB::raw('SUM(CASE WHEN kube.status = "Aktif" THEN 1 ELSE 0 END) as kube_aktif'),
                DB::raw('SUM(CASE WHEN kube.status = "Tidak Aktif" THEN 1 ELSE 0 END) as kube_tidak_aktif')
            )
            ->groupBy('kecamatan.id_kecamatan', 'kecamatan.nama_kecamatan', 'kategori.id_kategori', 'kategori.nama_kategori')
            ->orderBy('kecamatan.nama_kecamatan');

        if (!empty($id_kecamatan)) {
            $query->where('kecamatan.id_kecamatan', $id_kecamatan);
        }
        if (!empty($id_kategori)) {
            $query->where('kategori.id_kategori', $id_kategori);
        }

        $rekap = $query->get();

        $filterKecamatan = $id_kecamatan
            ? Kecamatan::find($id_kecamatan)?->nama_kecamatan
            : null;
        $filterKategori = $id_kategori
            ? KategoriKube::find($id_kategori)?->nama_kategori
            : null;

        $pdf = Pdf::loadView('admin.rekap_kube.pdf', compact(
            'rekap', 'filterKecamatan', 'filterKategori'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('rekap-kube.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new RekapKubeExport($request->id_kecamatan, $request->id_kategori),
            'rekap-kube.xlsx'
        );
    }

    public function detail($id_kecamatan, Request $request)
    {
        $id_kategori = $request->id_kategori;

        $query = Kube::with('desa.kecamatan', 'clusterUsaha.kategori')
            ->whereHas('desa', fn($q) => $q->where('id_kecamatan', $id_kecamatan));

        if (!empty($id_kategori)) {
            $query->whereHas('clusterUsaha', fn($q) => $q->where('id_kategori', $id_kategori));
        }

        $data          = $query->get();
        $namaKecamatan = optional($data->first()?->desa?->kecamatan)->nama_kecamatan ?? 'Tidak Diketahui';
        $namaKategori  = !empty($id_kategori) ? KategoriKube::find($id_kategori)?->nama_kategori : null;

        return view('admin.rekap_kube.detail', compact(
            'data', 'namaKecamatan', 'namaKategori', 'id_kategori'
        ));
    }
}