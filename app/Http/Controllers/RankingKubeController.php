<?php
namespace App\Http\Controllers;

use App\Exports\RankingKubeExport;
use App\Models\ViewRankingKube;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RankingKubeController extends Controller
{
    public function index(Request $request)
    {
        $tahun     = $request->tahun;
        $kategori  = $request->kategori;
        $kecamatan = $request->kecamatan;
        $status    = $request->status;

        $tahunList     = ViewRankingKube::select('tahun')->distinct()->orderByDesc('tahun')->pluck('tahun');
        $kategoriList  = ViewRankingKube::select('id_kategori', 'nama_kategori')->distinct()->get();
        $kecamatanList = ViewRankingKube::select('id_kecamatan', 'nama_kecamatan')->distinct()->get();

        $overall = ViewRankingKube::selectRaw('
                id_kube, nama_kube, id_kecamatan, nama_kecamatan,
                id_desa_kelurahan, nama_desa_kelurahan,
                id_cluster, nama_cluster, id_kategori, nama_kategori, status,
                SUM(total_omset) as total_omset,
                SUM(total_pengeluaran) as total_pengeluaran,
                SUM(total_laba_bersih) as total_laba_bersih
            ')
            ->groupBy(
                'id_kube', 'nama_kube', 'id_kecamatan', 'nama_kecamatan',
                'id_desa_kelurahan', 'nama_desa_kelurahan',
                'id_cluster', 'nama_cluster', 'id_kategori', 'nama_kategori', 'status'
            )
            ->orderByDesc('total_laba_bersih')
            ->get()->values();

        $overall->each(fn($item, $i) => $item->ranking_overall = $i + 1);

        $filtered = ViewRankingKube::selectRaw('
                id_kube, nama_kube, id_kecamatan, nama_kecamatan,
                id_desa_kelurahan, nama_desa_kelurahan,
                id_cluster, nama_cluster, id_kategori, nama_kategori, status,
                SUM(total_omset) as total_omset,
                SUM(total_pengeluaran) as total_pengeluaran,
                SUM(total_laba_bersih) as total_laba_bersih
            ')
            ->when($tahun,     fn($q) => $q->where('tahun', $tahun))
            ->when($kategori,  fn($q) => $q->where('id_kategori', $kategori))
            ->when($kecamatan, fn($q) => $q->where('id_kecamatan', $kecamatan))
            ->when($status,    fn($q) => $q->where('status', $status))
            ->groupBy(
                'id_kube', 'nama_kube', 'id_kecamatan', 'nama_kecamatan',
                'id_desa_kelurahan', 'nama_desa_kelurahan',
                'id_cluster', 'nama_cluster', 'id_kategori', 'nama_kategori', 'status'
            )
            ->orderByDesc('total_laba_bersih')
            ->get()->values();

        $filtered->each(fn($item, $i) => $item->ranking_filter = $i + 1);

        $overallMap = $overall->keyBy('id_kube');
        $filtered->each(fn($item) =>
            $item->ranking_overall = $overallMap->get($item->id_kube)?->ranking_overall ?? '-'
        );

        // ── Periode per KUBE ──────────────────────────────────────────────────
        $this->attachPeriode($filtered, $tahun, $kategori, $kecamatan, $status);

        $top10 = $overall->take(10);

        return view('admin.analisis_akreditasi.ranking_kube', compact(
            'overall', 'filtered', 'top10',
            'tahunList', 'kategoriList', 'kecamatanList'
        ));
    }

    // ── Helper: attach field `periode` ke collection $filtered ────────────────
    private function attachPeriode($filtered, $tahun, $kategori, $kecamatan, $status): void
    {
        if ($tahun) {
            // Filter tahun aktif → semua item periodenya ya tahun itu saja
            $filtered->each(fn($item) => $item->periode = (string) $tahun);
            return;
        }

        // Ambil semua kombinasi id_kube + tahun (filter lain tetap berlaku)
        $yearsByKube = ViewRankingKube::select('id_kube', 'tahun')
            ->when($kategori,  fn($q) => $q->where('id_kategori', $kategori))
            ->when($kecamatan, fn($q) => $q->where('id_kecamatan', $kecamatan))
            ->when($status,    fn($q) => $q->where('status', $status))
            ->whereIn('id_kube', $filtered->pluck('id_kube'))   // hanya KUBE yang tampil
            ->distinct()
            ->orderBy('tahun')
            ->get()
            ->groupBy('id_kube')
            ->map(fn($rows) => $rows->pluck('tahun')->sort()->values()->all());

        $filtered->each(function ($item) use ($yearsByKube) {
            $years = $yearsByKube->get($item->id_kube, []);

            $item->periode = match (true) {
                count($years) === 0 => '-',
                count($years) === 1 => (string) $years[0],
                default             => $years[0] . ' – ' . end($years),  // mis. 2021 – 2024
            };
        });
    }

    // ── Helper: ambil $filtered (dipakai pdf & excel) ─────────────────────────
    private function getFiltered(Request $request)
    {
        $tahun     = $request->tahun;
        $kategori  = $request->kategori;
        $kecamatan = $request->kecamatan;
        $status    = $request->status;

        $overall = ViewRankingKube::selectRaw('
                id_kube, nama_kube, id_kecamatan, nama_kecamatan,
                id_desa_kelurahan, nama_desa_kelurahan,
                id_cluster, nama_cluster, id_kategori, nama_kategori, status,
                SUM(total_omset) as total_omset,
                SUM(total_pengeluaran) as total_pengeluaran,
                SUM(total_laba_bersih) as total_laba_bersih
            ')
            ->groupBy(
                'id_kube', 'nama_kube', 'id_kecamatan', 'nama_kecamatan',
                'id_desa_kelurahan', 'nama_desa_kelurahan',
                'id_cluster', 'nama_cluster', 'id_kategori', 'nama_kategori', 'status'
            )
            ->orderByDesc('total_laba_bersih')
            ->get()->values();

        $overall->each(fn($item, $i) => $item->ranking_overall = $i + 1);

        $filtered = ViewRankingKube::selectRaw('
                id_kube, nama_kube, id_kecamatan, nama_kecamatan,
                id_desa_kelurahan, nama_desa_kelurahan,
                id_cluster, nama_cluster, id_kategori, nama_kategori, status,
                SUM(total_omset) as total_omset,
                SUM(total_pengeluaran) as total_pengeluaran,
                SUM(total_laba_bersih) as total_laba_bersih
            ')
            ->when($tahun,     fn($q) => $q->where('tahun', $tahun))
            ->when($kategori,  fn($q) => $q->where('id_kategori', $kategori))
            ->when($kecamatan, fn($q) => $q->where('id_kecamatan', $kecamatan))
            ->when($status,    fn($q) => $q->where('status', $status))
            ->groupBy(
                'id_kube', 'nama_kube', 'id_kecamatan', 'nama_kecamatan',
                'id_desa_kelurahan', 'nama_desa_kelurahan',
                'id_cluster', 'nama_cluster', 'id_kategori', 'nama_kategori', 'status'
            )
            ->orderByDesc('total_laba_bersih')
            ->get()->values();

        $filtered->each(fn($item, $i) => $item->ranking_filter = $i + 1);

        $overallMap = $overall->keyBy('id_kube');
        $filtered->each(fn($item) =>
            $item->ranking_overall = $overallMap->get($item->id_kube)?->ranking_overall ?? '-'
        );

        // ── Periode per KUBE ──────────────────────────────────────────────────
        $this->attachPeriode($filtered, $tahun, $kategori, $kecamatan, $status);

        return $filtered;
    }

    public function exportPdf(Request $request)
    {
        $filtered = $this->getFiltered($request);

        $pdf = Pdf::loadView('admin.analisis_akreditasi.ranking_kube_pdf', compact('filtered'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('ranking-kube.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filtered = $this->getFiltered($request);

        $filterAktif = collect([
            'Kecamatan' => $request->kecamatan ? $filtered->first()?->nama_kecamatan : null,
            'Tahun'     => $request->tahun,
            'Kategori'  => $request->kategori ? $filtered->first()?->nama_kategori : null,
            'Status'    => $request->status,
        ])->filter()->toArray();

        return Excel::download(
            new RankingKubeExport($filtered, $filterAktif),
            'ranking-kube-' . now()->format('Ymd') . '.xlsx'
        );
    }
}