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

        // Ketua KUBE tidak boleh melihat nominal (omset, pengeluaran, laba bersih)
        $hideNominal = auth()->user()->role === 'ketua_kube';

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

        // Top 10 mengikuti hasil filter, bukan keseluruhan data, supaya
        // chart dan tabel di bawahnya selalu konsisten dengan filter yang dipilih.
        $top10 = $filtered->take(10);

        // ── Keamanan: untuk Ketua KUBE, betul-betul hapus field nominal dari
        //    objek sebelum dikirim ke view, supaya tidak bisa dibaca lewat
        //    "View Page Source" / devtools meskipun di Blade disembunyikan.
        if ($hideNominal) {
            $this->stripNominal($overall);
            $this->stripNominal($filtered);
            $top10 = collect(); // chart top 10 tidak relevan tanpa nominal
        }

        return view('admin.analisis_akreditasi.ranking_kube', compact(
            'overall', 'filtered', 'top10',
            'tahunList', 'kategoriList', 'kecamatanList', 'hideNominal'
        ));
    }

    // ── Helper: hapus field nominal dari tiap item collection ──────────────────
    private function stripNominal($collection): void
    {
        $collection->each(function ($item) {
            unset($item->total_omset, $item->total_pengeluaran, $item->total_laba_bersih);
        });
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
        $filtered    = $this->getFiltered($request);
        $hideNominal = auth()->user()->role === 'ketua_kube';

        if ($hideNominal) {
            $this->stripNominal($filtered);
        }

        $pdf = Pdf::loadView('admin.analisis_akreditasi.ranking_kube_pdf', compact('filtered', 'hideNominal'))
            ->setPaper('a4', 'landscape');

        // Download langsung, bukan dibuka inline di browser
        return $pdf->download('ranking-kube-' . now()->format('Ymd') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filtered    = $this->getFiltered($request);
        $hideNominal = auth()->user()->role === 'ketua_kube';

        if ($hideNominal) {
            $this->stripNominal($filtered);
        }

        $filterAktif = collect([
            'Kecamatan' => $request->kecamatan ? $filtered->first()?->nama_kecamatan : null,
            'Tahun'     => $request->tahun,
            'Kategori'  => $request->kategori ? $filtered->first()?->nama_kategori : null,
            'Status'    => $request->status,
        ])->filter()->toArray();

        return Excel::download(
            new RankingKubeExport($filtered, $filterAktif, $hideNominal),
            'ranking-kube-' . now()->format('Ymd') . '.xlsx'
        );
    }
}