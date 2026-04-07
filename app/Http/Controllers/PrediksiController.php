<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kecamatan;
use App\Models\Kube;
use App\Models\Pertanyaan;
use App\Models\HasilPrediksi;
use Illuminate\Support\Facades\DB;

class PrediksiController extends Controller
{
    // HALAMAN UTAMA (SUDAH GANTI KE FORM)
    public function index()
    {
        $kecamatan = Kecamatan::all();
        $pertanyaan = Pertanyaan::all();

        // INI YANG DIUBAH
        return view('pendamping.prediksi.form', compact('kecamatan', 'pertanyaan'));
    }

    // GET KUBE
    public function getKube($id)
    {
        $kube = Kube::join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->where('desa_kelurahan.id_kecamatan', $id)
            ->select('kube.id_kube', 'kube.nama_kube')
            ->get();

        return response()->json($kube);
    }

    // DETAIL KUBE
    public function getDetail($id)
    {
        $kube = Kube::join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan', 'desa_kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->leftJoin('pendamping', 'kecamatan.id_kecamatan', '=', 'pendamping.id_kecamatan')
            ->where('kube.id_kube', $id)
            ->select(
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'pendamping.nama_pendamping',
                'pendamping.id_pendamping'
            )
            ->first();

        return response()->json($kube);
    }
    

    
public function store(Request $request)
{
    // 1. Validasi data dasar
    $request->validate([
        'id_kube' => 'required|integer',
        'id_pendamping' => 'nullable|integer',
        'bulan' => 'required|integer|min:1|max:12',
        'tahun' => 'required|integer|min:2000|max:' . date('Y'),
        'jawaban' => 'required|array', // Pastikan input jawaban ada
        'catatan' => 'required|array', // Pastikan input catatan ada
    ]);

    // 2. AMBIL DATA DARI REQUEST (Penting: Tanpa ini, validasi di bawah pasti gagal)
    $jawabanArr = $request->input('jawaban', []);
    $catatanArr = $request->input('catatan', []);

    $pertanyaan = Pertanyaan::all();

    // 3. VALIDASI APAKAH SEMUA PERTANYAAN DI DATABASE SUDAH DIJAWAB DI FORM
    foreach ($pertanyaan as $p) {
        $pid = $p->id_pertanyaan;

        // Cek apakah ID pertanyaan ini ada di array jawaban dan catatan
        if (!isset($jawabanArr[$pid]) || !isset($catatanArr[$pid]) || trim($catatanArr[$pid]) === '') {
            return back()->withInput()->with('error', 'Semua pertanyaan harus diisi!');
        }
    }

    DB::beginTransaction();

    try {
        $idPrediksi = time(); // Unique ID untuk grup prediksi ini

        foreach ($pertanyaan as $p) {
            $pid = $p->id_pertanyaan;

            HasilPrediksi::create([
                'id_prediksi'   => $idPrediksi,
                'id_kube'       => $request->input('id_kube'),
                'id_pendamping' => $request->input('id_pendamping'),
                'id_pertanyaan' => $pid,
                'jawaban'       => $jawabanArr[$pid] === 'ya' ? 1 : 0,
                'catatan'       => $catatanArr[$pid],
                'bulan'         => $request->input('bulan'),
                'tahun'         => $request->input('tahun'),
            ]);
        }

        DB::commit();

        // 4. AMBIL ULANG DATA UNTUK HALAMAN HASIL
        $hasilPrediksi = HasilPrediksi::where('id_prediksi', $idPrediksi)->get();
        $kube = Kube::find($request->input('id_kube'));
        
        // Ambil data pendamping secara manual atau via Join
        $pendamping = DB::table('pendamping')
            ->where('id_pendamping', $request->input('id_pendamping'))
            ->first();

        // 5. HITUNG SKOR
        $totalPoin = $hasilPrediksi->sum('jawaban');
        $totalPertanyaan = $pertanyaan->count();
        $persentase = $totalPertanyaan > 0 ? ($totalPoin / $totalPertanyaan) * 100 : 0;
        
        // Logika status: Berhasil jika jawaban "Ya" minimal 4 (sesuai kode kamu)
        $status = $totalPoin >= 4 ? 'berhasil' : 'gagal';

        // 6. LEMPAR KE VIEW HASIL
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
        return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
    }
}
}