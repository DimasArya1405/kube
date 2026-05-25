<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kube;
use App\Models\Pertanyaan;
use App\Models\HasilPrediksi;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PrediksiController extends Controller
{
    /**
     * Ambil data pendamping berdasarkan user login
     * users.email = pendamping.email
     */
    private function getPendampingLogin()
    {
        
        $user = auth()->user();

        if (!$user) {
            abort(403, 'User belum login.');
        }

        if ($user->role !== 'pendamping') {
            abort(403, 'Akses hanya untuk pendamping.');
        }

        $pendamping = DB::table('pendamping')
            ->where('email', $user->email)
            ->first();

        if (!$pendamping) {
            abort(403, 'Data pendamping tidak ditemukan.');
        }

        return $pendamping;
    }

    /**
     * Validasi admin login
     */
    private function getAdminLogin()
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'User belum login.');
        }

        if ($user->role !== 'admin') {
            abort(403, 'Akses hanya untuk admin.');
        }

        return $user;
    }

    /**
     * Cek apakah KUBE memang ditugaskan ke pendamping login
     * berdasarkan tabel pembagian_pendamping
     */
    private function kubeMilikPendamping($idKube, $idPendamping)
    {
        return DB::table('pembagian_pendamping')
            ->where('id_kube', $idKube)
            ->where('id_pendamping', $idPendamping)
            ->where('status', 'Aktif')
            ->exists();
    }

    /**
     * Ambil semua KUBE yang ditugaskan ke pendamping login
     */
    private function getKubePendamping($idPendamping)
    {
        return DB::table('pembagian_pendamping')
            ->join('kube', 'pembagian_pendamping.id_kube', '=', 'kube.id_kube')
            ->where('pembagian_pendamping.id_pendamping', $idPendamping)
            ->where('pembagian_pendamping.status', 'Aktif')
            ->select('kube.id_kube', 'kube.nama_kube')
            ->distinct()
            ->get();
    }

    /**
     * HALAMAN FORM PREDIKSI
     * Kecamatan tetap ditampilkan dari data pendamping login
     * KUBE nanti diambil dari tabel pembagian_pendamping
     */
    public function index()
    {
        $pendamping = $this->getPendampingLogin();

        $kecamatan = Kecamatan::where('id_kecamatan', $pendamping->id_kecamatan)->get();
        $pertanyaan = Pertanyaan::all();

        return view('pendamping.prediksi.form', compact('kecamatan', 'pertanyaan', 'pendamping'));
    }

    /**
     * GET DATA KUBE
     * Sekarang diambil dari tabel pembagian_pendamping
     * Tidak lagi berdasarkan kecamatan
     */
    public function getKube()
    {
        $pendamping = $this->getPendampingLogin();

        $kube = $this->getKubePendamping($pendamping->id_pendamping);

        return response()->json($kube);
    }

    /**
     * DETAIL KUBE
     * Hanya bisa melihat detail KUBE yang memang ditugaskan
     */
    public function getDetail($id)
    {
        $pendampingLogin = $this->getPendampingLogin();

        if (!$this->kubeMilikPendamping($id, $pendampingLogin->id_pendamping)) {
            return response()->json(['message' => 'Data KUBE tidak ditemukan.'], 404);
        }

        $kube = DB::table('kube')
            ->join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan', 'desa_kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->where('kube.id_kube', $id)
            ->select(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan'
            )
            ->first();

        if (!$kube) {
            return response()->json(['message' => 'Data KUBE tidak ditemukan.'], 404);
        }

        return response()->json([
            'nama_kube'       => $kube->nama_kube,
            'nama_kecamatan'  => $kube->nama_kecamatan,
            'nama_pendamping' => $pendampingLogin->nama_pendamping,
            'id_pendamping'   => $pendampingLogin->id_pendamping,
        ]);
    }

    /**
     * SIMPAN HASIL PREDIKSI
     */
    public function store(Request $request)
    {
        $pendamping = $this->getPendampingLogin();

        $request->validate([
            'id_kube' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:' . (date('Y') - 2) . '|max:' . date('Y'),
            'jawaban' => 'required|array',
            'catatan' => 'nullable|array',
        ]);

        if (!$this->kubeMilikPendamping($request->id_kube, $pendamping->id_pendamping)) {
            return back()->withInput()->with('error', 'KUBE tidak sesuai dengan penugasan pendamping yang login.');
        }

        $jawabanArr = $request->input('jawaban');
        $catatanArr = $request->input('catatan');

        $pertanyaan = Pertanyaan::all();

        foreach ($pertanyaan as $p) {
            $pid = $p->id;

            if (!isset($jawabanArr[$pid])) {
                return back()->withInput()->with('error', 'Semua pertanyaan harus diisi!');
            }
        }

        $cekSudahAda = DB::table('hasil_prediksi')
            ->where('id_kube', $request->id_kube)
            ->where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($cekSudahAda) {
            return back()->withInput()->with('error', 'Prediksi untuk KUBE ini pada bulan dan tahun tersebut sudah ada.');
        }

        DB::beginTransaction();

        try {
            $idPrediksi = time();

            foreach ($pertanyaan as $p) {
                $pid = $p->id;

                HasilPrediksi::create([
                    'id_prediksi'   => $idPrediksi,
                    'id_kube'       => $request->id_kube,
                    'id_pendamping' => $pendamping->id_pendamping,
                    'id_pertanyaan' => $pid,
                    'jawaban'       => $jawabanArr[$pid] === 'ya' ? 1 : 0,
                    'catatan'       => $catatanArr[$pid] ?? null,
                    'bulan'         => $request->bulan,
                    'tahun'         => $request->tahun,
                ]);
            }

            DB::commit();

            $hasilPrediksi = HasilPrediksi::where('id_prediksi', $idPrediksi)->get();
            $kube = Kube::find($request->input('id_kube'));

            $totalPoin = $hasilPrediksi->sum('jawaban');
            $totalPertanyaan = $pertanyaan->count();
            $persentase = $totalPertanyaan > 0 ? ($totalPoin / $totalPertanyaan) * 100 : 0;
            $status = $totalPoin >= 4 ? 'berhasil' : 'gagal';

            return view('pendamping.prediksi.hasil', compact(
                'kube',
                'pendamping',
                'pertanyaan',
                'hasilPrediksi',
                'totalPoin',
                'persentase',
                'status'
            ))->with([
                'bulan' => $request->input('bulan'),
                'tahun' => $request->input('tahun')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * DAFTAR PREDIKSI
     * Hanya milik pendamping login
     */
    public function daftarPrediksi(Request $request)
    {
        $pendamping = $this->getPendampingLogin();

        $tahun = $request->tahun;
        $search = $request->search;

        $tahunMin = date('Y') - 2;
        $tahunMax = date('Y');

        $query = DB::table('hasil_prediksi')
            ->join('kube', 'hasil_prediksi.id_kube', '=', 'kube.id_kube')
            ->join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan', 'desa_kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('pendamping', 'hasil_prediksi.id_pendamping', '=', 'pendamping.id_pendamping')
            ->where('hasil_prediksi.id_pendamping', $pendamping->id_pendamping)
            ->select(
                'hasil_prediksi.id_prediksi',
                'hasil_prediksi.id_kube',
                'kecamatan.nama_kecamatan',
                'kube.nama_kube',
                'pendamping.nama_pendamping',
                'hasil_prediksi.bulan',
                'hasil_prediksi.tahun',
                DB::raw('SUM(hasil_prediksi.jawaban) as total_ya'),
                DB::raw('COUNT(hasil_prediksi.id_prediksi) as total_pertanyaan'),
                DB::raw('MAX(hasil_prediksi.created_at) as terakhir_ditambahkan'),
                DB::raw("
                    CASE 
                        WHEN SUM(hasil_prediksi.jawaban) >= 4 THEN 'Berhasil'
                        ELSE 'Gagal'
                    END as status
                ")
            )
            ->whereBetween('hasil_prediksi.tahun', [$tahunMin, $tahunMax])
            ->groupBy(
                'hasil_prediksi.id_prediksi',
                'hasil_prediksi.id_kube',
                'kecamatan.nama_kecamatan',
                'kube.nama_kube',
                'pendamping.nama_pendamping',
                'hasil_prediksi.bulan',
                'hasil_prediksi.tahun'
            );

        if ($tahun) {
            $query->where('hasil_prediksi.tahun', $tahun);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kecamatan.nama_kecamatan', 'like', '%' . $search . '%')
                  ->orWhere('kube.nama_kube', 'like', '%' . $search . '%')
                  ->orWhere('pendamping.nama_pendamping', 'like', '%' . $search . '%');
            });
        }

        $dataPrediksi = $query->orderByDesc('terakhir_ditambahkan')
            ->paginate(10)
            ->withQueryString();

        $statQuery = DB::table('hasil_prediksi')
            ->where('id_pendamping', $pendamping->id_pendamping)
            ->select(
                'id_prediksi',
                DB::raw('SUM(jawaban) as total_ya'),
                DB::raw("
                    CASE 
                        WHEN SUM(jawaban) >= 4 THEN 'Berhasil'
                        ELSE 'Gagal'
                    END as status
                ")
            )
            ->whereBetween('tahun', [$tahunMin, $tahunMax])
            ->groupBy('id_prediksi');

        if ($tahun) {
            $statQuery->where('tahun', $tahun);
        }

        $statData = $statQuery->get();

        $jumlahBerhasil = $statData->where('status', 'Berhasil')->count();
        $jumlahGagal = $statData->where('status', 'Gagal')->count();
        $totalPrediksi = $statData->count();

        $tahunList = collect(range($tahunMin, $tahunMax))->sortDesc();

        return view('pendamping.prediksi.daftar', compact(
            'dataPrediksi',
            'jumlahBerhasil',
            'jumlahGagal',
            'totalPrediksi',
            'tahunList'
        ));
    }

    /**
     * DETAIL PREDIKSI
     */
    public function detailPrediksi($id_prediksi)
    {
        $pendamping = $this->getPendampingLogin();

        $data = DB::table('hasil_prediksi')
            ->join('pertanyaan', 'hasil_prediksi.id_pertanyaan', '=', 'pertanyaan.id')
            ->join('kube', 'hasil_prediksi.id_kube', '=', 'kube.id_kube')
            ->leftJoin('pendamping', 'hasil_prediksi.id_pendamping', '=', 'pendamping.id_pendamping')
            ->where('hasil_prediksi.id_prediksi', $id_prediksi)
            ->where('hasil_prediksi.id_pendamping', $pendamping->id_pendamping)
            ->select(
                'hasil_prediksi.*',
                'pertanyaan.pertanyaan',
                'kube.nama_kube',
                'pendamping.nama_pendamping'
            )
            ->get();

        if ($data->isEmpty()) {
            return redirect()->route('prediksi.daftar')->with('error', 'Data prediksi tidak ditemukan.');
        }

        $first = $data->first();
        $totalYa = $data->sum('jawaban');
        $status = $totalYa >= 4 ? 'Berhasil' : 'Gagal';

        return view('pendamping.prediksi.detail', compact('data', 'first', 'totalYa', 'status'));
    }

    /**
     * FORM EDIT PREDIKSI
     */
    public function editPrediksi($id_prediksi)
    {
        $pendamping = $this->getPendampingLogin();

        $data = DB::table('hasil_prediksi')
            ->join('pertanyaan', 'hasil_prediksi.id_pertanyaan', '=', 'pertanyaan.id')
            ->join('kube', 'hasil_prediksi.id_kube', '=', 'kube.id_kube')
            ->leftJoin('pendamping', 'hasil_prediksi.id_pendamping', '=', 'pendamping.id_pendamping')
            ->where('hasil_prediksi.id_prediksi', $id_prediksi)
            ->where('hasil_prediksi.id_pendamping', $pendamping->id_pendamping)
            ->select(
                'hasil_prediksi.*',
                'pertanyaan.pertanyaan',
                'kube.nama_kube',
                'pendamping.nama_pendamping'
            )
            ->get();

        if ($data->isEmpty()) {
            return redirect()->route('prediksi.daftar')->with('error', 'Data prediksi tidak ditemukan.');
        }

        $first = $data->first();

        return view('pendamping.prediksi.edit', compact('data', 'first'));
    }

    /**
     * UPDATE PREDIKSI
     */
    public function updatePrediksi(Request $request, $id_prediksi)
    {
        $pendamping = $this->getPendampingLogin();

        $request->validate([
            'jawaban' => 'required|array',
            'catatan' => 'nullable|array',
        ]);

        $dataLama = HasilPrediksi::where('id_prediksi', $id_prediksi)
            ->where('id_pendamping', $pendamping->id_pendamping)
            ->get();

        if ($dataLama->isEmpty()) {
            return redirect()->route('prediksi.daftar')->with('error', 'Data prediksi tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $adaPerubahan = false;

            foreach ($dataLama as $item) {
                $idPertanyaan = $item->id_pertanyaan;

                $jawabanBaru = ($request->jawaban[$idPertanyaan] ?? 'tidak') === 'ya' ? 1 : 0;
                $catatanBaru = $request->catatan[$idPertanyaan] ?? null;

                $catatanLama = $item->catatan ?? null;
                $catatanBaru = $catatanBaru !== '' ? $catatanBaru : null;

                if ($item->jawaban != $jawabanBaru || $catatanLama != $catatanBaru) {
                    $adaPerubahan = true;

                    $item->update([
                        'jawaban' => $jawabanBaru,
                        'catatan' => $catatanBaru,
                    ]);
                }
            }

            DB::commit();

            if ($adaPerubahan) {
                return redirect()->route('prediksi.daftar')
                    ->with('success', 'Data prediksi berhasil diperbarui.');
            } else {
                return redirect()->route('prediksi.daftar')
                    ->with('info', 'Tidak ada perubahan data.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * TRACK RECORD
     */
    public function trackRecord($id_kube, $tahun)
    {
        $pendamping = $this->getPendampingLogin();

        if (!$this->kubeMilikPendamping($id_kube, $pendamping->id_pendamping)) {
            return redirect()->route('prediksi.daftar')->with('error', 'KUBE tidak sesuai dengan penugasan pendamping.');
        }

        $tahunMin = date('Y') - 2;
        $tahunMax = date('Y');

        if ($tahun < $tahunMin || $tahun > $tahunMax) {
            $tahun = $tahunMax;
        }

        $kube = DB::table('kube')
            ->where('id_kube', $id_kube)
            ->first();

        $data = DB::table('hasil_prediksi')
            ->where('id_kube', $id_kube)
            ->where('id_pendamping', $pendamping->id_pendamping)
            ->where('tahun', $tahun)
            ->select(
                'id_prediksi',
                'bulan',
                DB::raw('SUM(jawaban) as total_ya')
            )
            ->groupBy('id_prediksi', 'bulan')
            ->get();

        $prediksiPerBulan = [];
        foreach ($data as $item) {
            $status = $item->total_ya >= 4 ? 'Berhasil' : 'Gagal';

            $prediksiPerBulan[$item->bulan] = [
                'id_prediksi' => $item->id_prediksi,
                'status' => $status
            ];
        }

        $tahunList = collect(range($tahunMin, $tahunMax))->sortDesc();

        return view('pendamping.prediksi.track', compact(
            'kube',
            'tahun',
            'prediksiPerBulan',
            'tahunList'
        ));
    }

    /**
     * CEK BULAN TERSEDIA
     */
    public function getBulanTersedia(Request $request)
    {
        $pendamping = $this->getPendampingLogin();

        $idKube = $request->id_kube;
        $tahun = $request->tahun;

        if (!$idKube || !$tahun) {
            return response()->json([
                'bulan_terpakai' => [],
            ]);
        }

        if (!$this->kubeMilikPendamping($idKube, $pendamping->id_pendamping)) {
            return response()->json([
                'bulan_terpakai' => [],
            ]);
        }

        $bulanTerpakai = DB::table('hasil_prediksi')
            ->where('id_kube', $idKube)
            ->where('tahun', $tahun)
            ->pluck('bulan')
            ->unique()
            ->values();

        return response()->json([
            'bulan_terpakai' => $bulanTerpakai,
        ]);
    }

    /**
     * ============================
     * ADMIN - READ ONLY
     * ============================
     */

    public function daftarPrediksiAdmin(Request $request)
    {
        $this->getAdminLogin();

        $tahun = $request->tahun;
        $search = $request->search;

        $tahunMin = date('Y') - 2;
        $tahunMax = date('Y');

        $query = DB::table('hasil_prediksi')
            ->join('kube', 'hasil_prediksi.id_kube', '=', 'kube.id_kube')
            ->join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan', 'desa_kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('pendamping', 'hasil_prediksi.id_pendamping', '=', 'pendamping.id_pendamping')
            ->select(
                'hasil_prediksi.id_prediksi',
                'hasil_prediksi.id_kube',
                'kecamatan.nama_kecamatan',
                'kube.nama_kube',
                'pendamping.nama_pendamping',
                'hasil_prediksi.bulan',
                'hasil_prediksi.tahun',
                DB::raw('SUM(hasil_prediksi.jawaban) as total_ya'),
                DB::raw('COUNT(hasil_prediksi.id_prediksi) as total_pertanyaan'),
                DB::raw('MAX(hasil_prediksi.created_at) as terakhir_ditambahkan'),
                DB::raw("
                    CASE 
                        WHEN SUM(hasil_prediksi.jawaban) >= 4 THEN 'Berhasil'
                        ELSE 'Gagal'
                    END as status
                ")
            )
            ->whereBetween('hasil_prediksi.tahun', [$tahunMin, $tahunMax])
            ->groupBy(
                'hasil_prediksi.id_prediksi',
                'hasil_prediksi.id_kube',
                'kecamatan.nama_kecamatan',
                'kube.nama_kube',
                'pendamping.nama_pendamping',
                'hasil_prediksi.bulan',
                'hasil_prediksi.tahun'
            );

        if ($tahun) {
            $query->where('hasil_prediksi.tahun', $tahun);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kecamatan.nama_kecamatan', 'like', '%' . $search . '%')
                  ->orWhere('kube.nama_kube', 'like', '%' . $search . '%')
                  ->orWhere('pendamping.nama_pendamping', 'like', '%' . $search . '%');
            });
        }

        $dataPrediksi = $query->orderByDesc('terakhir_ditambahkan')
            ->paginate(10)
            ->withQueryString();

        $statQuery = DB::table('hasil_prediksi')
            ->select(
                'id_prediksi',
                DB::raw('SUM(jawaban) as total_ya'),
                DB::raw("
                    CASE 
                        WHEN SUM(jawaban) >= 4 THEN 'Berhasil'
                        ELSE 'Gagal'
                    END as status
                ")
            )
            ->whereBetween('tahun', [$tahunMin, $tahunMax])
            ->groupBy('id_prediksi');

        if ($tahun) {
            $statQuery->where('tahun', $tahun);
        }

        $statData = $statQuery->get();

        $jumlahBerhasil = $statData->where('status', 'Berhasil')->count();
        $jumlahGagal = $statData->where('status', 'Gagal')->count();
        $totalPrediksi = $statData->count();

        $tahunList = collect(range($tahunMin, $tahunMax))->sortDesc();

        return view('admin.analisis_akreditasi.prediksi_kube.daftar', compact(
            'dataPrediksi',
            'jumlahBerhasil',
            'jumlahGagal',
            'totalPrediksi',
            'tahunList'
        ));
    }

    public function detailPrediksiAdmin($id_prediksi)
    {
        $this->getAdminLogin();

        $data = DB::table('hasil_prediksi')
            ->join('pertanyaan', 'hasil_prediksi.id_pertanyaan', '=', 'pertanyaan.id')
            ->join('kube', 'hasil_prediksi.id_kube', '=', 'kube.id_kube')
            ->leftJoin('pendamping', 'hasil_prediksi.id_pendamping', '=', 'pendamping.id_pendamping')
            ->where('hasil_prediksi.id_prediksi', $id_prediksi)
            ->select(
                'hasil_prediksi.*',
                'pertanyaan.pertanyaan',
                'kube.nama_kube',
                'pendamping.nama_pendamping'
            )
            ->get();

        if ($data->isEmpty()) {
            return redirect()->route('admin.prediksi-kube.daftar')->with('error', 'Data prediksi tidak ditemukan.');
        }

        $first = $data->first();
        $totalYa = $data->sum('jawaban');
        $status = $totalYa >= 4 ? 'Berhasil' : 'Gagal';

        return view('admin.analisis_akreditasi.prediksi_kube.detail', compact('data', 'first', 'totalYa', 'status'));
    }

    public function trackRecordAdmin($id_kube, $tahun)
    {
        $this->getAdminLogin();

        $tahunMin = date('Y') - 2;
        $tahunMax = date('Y');

        if ($tahun < $tahunMin || $tahun > $tahunMax) {
            $tahun = $tahunMax;
        }

        $kube = DB::table('kube')
            ->where('id_kube', $id_kube)
            ->first();

        if (!$kube) {
            return redirect()->route('admin.prediksi-kube.daftar')->with('error', 'Data KUBE tidak ditemukan.');
        }

        $data = DB::table('hasil_prediksi')
            ->where('id_kube', $id_kube)
            ->where('tahun', $tahun)
            ->select(
                'id_prediksi',
                'bulan',
                DB::raw('SUM(jawaban) as total_ya')
            )
            ->groupBy('id_prediksi', 'bulan')
            ->get();

        $prediksiPerBulan = [];
        foreach ($data as $item) {
            $status = $item->total_ya >= 4 ? 'Berhasil' : 'Gagal';

            $prediksiPerBulan[$item->bulan] = [
                'id_prediksi' => $item->id_prediksi,
                'status' => $status
            ];
        }

        $tahunList = collect(range($tahunMin, $tahunMax))->sortDesc();

        return view('admin.analisis_akreditasi.prediksi_kube.track', compact(
            'kube',
            'tahun',
            'prediksiPerBulan',
            'tahunList'
        ));
    }
}