<?php
namespace App\Http\Controllers;

use App\Models\ViewRankingKube;
use Illuminate\Http\Request;

class RankingKubeController extends Controller
{
    public function index(Request $request)
    {
        $tahun     = $request->tahun;
        $kategori  = $request->kategori;
        $kecamatan = $request->kecamatan;
        $status    = $request->status;

        // === Opsi Dropdown ===
        $tahunList     = ViewRankingKube::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');
        $kategoriList  = ViewRankingKube::select('id_kategori', 'nama_kategori')->distinct()->get();
        $kecamatanList = ViewRankingKube::select('id_kecamatan', 'nama_kecamatan')->distinct()->get();

        // === 1. Ranking Overall (SUM semua tahun, tanpa filter apapun) ===
        $overall = ViewRankingKube::selectRaw('
                id_kube,
                nama_kube,
                id_kecamatan,
                nama_kecamatan,
                id_desa_kelurahan,
                nama_desa_kelurahan,
                id_cluster,
                nama_cluster,
                id_kategori,
                nama_kategori,
                status,
                SUM(total_omset) as total_omset,
                SUM(total_pengeluaran) as total_pengeluaran,
                SUM(total_laba_bersih) as total_laba_bersih
            ')
            ->groupBy(
                'id_kube', 'nama_kube',
                'id_kecamatan', 'nama_kecamatan',
                'id_desa_kelurahan', 'nama_desa_kelurahan',
                'id_cluster', 'nama_cluster',
                'id_kategori', 'nama_kategori',
                'status'
            )
            ->orderByDesc('total_laba_bersih')
            ->get()
            ->values();

        $overall->each(fn($item, $i) => $item->ranking_overall = $i + 1);

        // === 2. Filtered (pakai semua filter, SUM per KUBE dalam filter tsb) ===
        $filtered = ViewRankingKube::selectRaw('
                id_kube,
                nama_kube,
                id_kecamatan,
                nama_kecamatan,
                id_desa_kelurahan,
                nama_desa_kelurahan,
                id_cluster,
                nama_cluster,
                id_kategori,
                nama_kategori,
                status,
                SUM(total_omset) as total_omset,
                SUM(total_pengeluaran) as total_pengeluaran,
                SUM(total_laba_bersih) as total_laba_bersih
            ')
            ->when($tahun,     fn($q) => $q->where('tahun', $tahun))
            ->when($kategori,  fn($q) => $q->where('id_kategori', $kategori))
            ->when($kecamatan, fn($q) => $q->where('id_kecamatan', $kecamatan))
            ->when($status,    fn($q) => $q->where('status', $status))
            ->groupBy(
                'id_kube', 'nama_kube',
                'id_kecamatan', 'nama_kecamatan',
                'id_desa_kelurahan', 'nama_desa_kelurahan',
                'id_cluster', 'nama_cluster',
                'id_kategori', 'nama_kategori',
                'status'
            )
            ->orderByDesc('total_laba_bersih')
            ->get()
            ->values();

        $filtered->each(fn($item, $i) => $item->ranking_filter = $i + 1);

        // Gabungkan ranking_overall ke $filtered
        $overallMap = $overall->keyBy('id_kube');
        $filtered->each(function ($item) use ($overallMap) {
            $item->ranking_overall = $overallMap->get($item->id_kube)?->ranking_overall ?? '-';
        });

        // Top 10 untuk chart (dari overall)
        $top10 = $overall->take(10);

        return view('admin.analisis_akreditasi.ranking_kube', compact(
            'overall', 'filtered', 'top10',
            'tahunList', 'kategoriList', 'kecamatanList'
        ));
    }

    public function exportPdf(Request $request)
    {
        return back()->with('info', 'Fitur export PDF belum tersedia.');
    }

    public function exportExcel(Request $request)
    {
        return back()->with('info', 'Fitur export Excel belum tersedia.');
    }
}