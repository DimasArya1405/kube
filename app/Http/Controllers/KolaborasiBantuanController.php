<?php

namespace App\Http\Controllers;
use App\Models\KolaborasiBantuan;
use App\Models\Mitra;
use App\Models\Kube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KolaborasiExport;
use App\Models\KategoriKube;
class KolaborasiBantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. HAPUS 'kube' dari with() utama agar proses ->get() tidak mengalami error "Array to string"
        $query = KolaborasiBantuan::with(['mitra', 'buktiPenyaluran.dokumentasi']);

        // Filter jika datang dari tombol di halaman Mitra (tetap utuh)
        if ($request->has('id_mitra')) {
            $query->where('id_mitra', $request->id_mitra);
        }

        // Filter Berdasarkan Tahun (tetap utuh)
        if ($request->filled('tahun')) {
            $query->whereYear('tgl_pelaksanaan', $request->tahun);
        }

        // 2. Proses penarikan data dijamin lancar dan sukses di sini!
        $bantuans = $query->get();

        // 3. JEMBATAN PENYELAMAT: Kita buat relasi 'kube' tiruan secara manual setelah data ditarik
        // agar halaman View Blade Anda yang menggunakan data lama maupun data baru TIDAK RUSAK/ERROR.
        foreach ($bantuans as $bantuan) {
            if (is_array($bantuan->id_kube)) {
                // Jika data baru (Array JSON): Kita isi properti 'kube' dengan data KUBE pertama sebagai formalitas
                $bantuan->setRelation('kube', Kube::find(collect($bantuan->id_kube)->first()));
            } else {
                // Jika data lama (Integer/String tunggal): Kita panggil relasi aslinya secara manual
                $bantuan->load('kube');
            }
        }

        // --- SISA KODE DI BAWAH INI TETAP UTUH & TIDAK BERUBAH SAMA SEKALI ---
        
        // Ambil daftar tahun unik dari database untuk isi dropdown filter
        $listTahun = KolaborasiBantuan::selectRaw('YEAR(tgl_pelaksanaan) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        
        // Ambil data pendukung untuk dropdown di modal tambah
        $mitras = Mitra::all();
        
        // Ambil semua kategori untuk dropdown pertama
        $kategoris = \App\Models\KategoriKube::all();

        $kubes = Kube::with('clusterUsaha')->where('status', 'Aktif')->get();
        
        // Data mitra yang sedang difilter (untuk info di header)
        $filterMitra = $request->has('id_mitra') ? Mitra::find($request->id_mitra) : null;

        return view('admin.alur_bantuan.bantuan', compact('bantuans', 'mitras', 'kubes', 'filterMitra', 'listTahun', 'kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi input (Tetap memvalidasi id_kube sebagai array)
        $validated = $request->validate([
            'id_mitra'        => 'required',
            'id_kube'         => 'required|array', // Harus berupa array
            'id_kube.*'       => 'required',       
            'jenis_bantuan'   => 'required',
            'nama_bantuan'    => 'required',
            'tgl_pelaksanaan' => 'required|date',
            'bantuan'         => 'required',
            'deskripsi'       => 'required',
            'foto_bukti'      => 'required|image|max:5120',
        ]);

        // Buat variabel awal untuk menampung nama file foto
        $nama_file = null;

        // 2. Proses upload foto (Cukup 1 kali upload)
        if ($request->hasFile('foto_bukti')) {
            $file = $request->file('foto_bukti');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->storeAs('bantuan', $nama_file, 'public');
        }

        // 3. Simpan data SEKALIGUS ke dalam SATU baris (Tanpa looping foreach)
        KolaborasiBantuan::create([
            'id_mitra'        => $request->id_mitra,
            'id_kube'         => $request->id_kube, // LANGSUNG masukkan array-nya di sini
            'jenis_bantuan'   => $request->jenis_bantuan,
            'nama_bantuan'    => $request->nama_bantuan,
            'tgl_pelaksanaan' => $request->tgl_pelaksanaan,
            'bantuan'         => $request->bantuan,
            'deskripsi'       => $request->deskripsi,
            'foto_bukti'      => $nama_file, 
            'status'          => 'Terencana' 
        ]);

        // 4. Kembalikan ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Data bantuan KUBE berhasil disimpan menjadi satu baris!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        // 1. Cari data bantuan berdasarkan ID
        $bantuan = KolaborasiBantuan::findOrFail($id);
        
        // 2. Validasi input data (id_kube wajib berupa array karena checkbox)
        $request->validate([
            'id_mitra'        => 'required',
            'id_kube'         => 'required|array', 
            'jenis_bantuan'   => 'required',
            'tgl_pelaksanaan' => 'required|date',
            'nama_bantuan'    => 'required|string|max:255',
            'bantuan'         => 'required',
            'deskripsi'       => 'required',
            'status'          => 'required',
            'foto_bukti'      => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // 3. Ambil seluruh data dari request form
        $data = $request->all();

        // 4. KONVERSI DATA: Ubah array id_kube menjadi format ["1","2"] agar seragam di DB
        if (isset($data['id_kube']) && is_array($data['id_kube'])) {
            $data['id_kube'] = json_encode($data['id_kube']);
        }

        // 5. Manajemen file upload foto bukti lama & baru
        if ($request->hasFile('foto_bukti')) {
            // Hapus file foto lama di server agar storage tidak penuh
            if ($bantuan->foto_bukti && Storage::exists('public/bantuan/' . $bantuan->foto_bukti)) {
                Storage::delete('public/bantuan/' . $bantuan->foto_bukti);
            }

            // Upload berkas gambar baru ke folder storage/app/public/bantuan
            $file = $request->file('foto_bukti');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->storeAs('public/bantuan', $nama_file);
            $data['foto_bukti'] = $nama_file;
        } else {
            // Jika user tidak mengganti foto, pertahankan berkas nama foto lama
            unset($data['foto_bukti']);
        }

        // 6. Update mass assignment ke dalam database
        $bantuan->update($data);

        // 7. Kembalikan ke halaman sebelumnya dengan alert sukses
        return redirect()->back()->with('success', 'Data diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bantuan = KolaborasiBantuan::findOrFail($id);
        
        // Hapus foto bukti dari storage
        if ($bantuan->foto_bukti) {
            Storage::delete('public/bantuan/' . $bantuan->foto_bukti);
        }

        $bantuan->delete();

        return redirect()->back()->with('success', 'Data bantuan berhasil dihapus');
    }
    public function lihatFoto( string $filename)
    {
        $filename = rawurldecode($filename);
        $path = 'private/public/bantuan/' . $filename;
        $fullPath = storage_path('app/' . $path);

        if (!file_exists($fullPath)) {
       
        $altPath = storage_path('app/public/bantuan/' . $filename);
        if (file_exists($altPath)) {
            return response()->file($altPath);
        }
        
        abort(404, "File tidak ada di: " . $fullPath);
        }
        
        // Mengembalikan file agar bisa dilihat di browser 
        return response()->file($fullPath);
    }

    public function exportPdf( Request $request)
    {
        $query = KolaborasiBantuan::with(['mitra', 'kube']);

        
        $kolomTanggal = 'tgl_pelaksanaan'; 

        if ($request->filled('id_mitra')) {
            $query->where('id_mitra', $request->id_mitra);
        }

        if ($request->filled('tahun')) {
            $query->whereYear($kolomTanggal, $request->tahun);
        }
        $data = $query->latest()->get();
        
        $pdf = Pdf::loadView('admin.alur_bantuan.kolaborasi_pdf', compact('data'))
                ->setPaper('a4', 'landscape'); 
                
        return $pdf->download('Laporan_Kolaborasi_Bantuan.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new KolaborasiExport($request), 'Laporan_Kolaborasi_Bantuan.xlsx');
    }
}
