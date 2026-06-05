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

class KolaborasiBantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KolaborasiBantuan::with(['mitra', 'kube', 'buktiPenyaluran.dokumentasi']);

        // Filter jika datang dari tombol di halaman Mitra
        if ($request->has('id_mitra')) {
            $query->where('id_mitra', $request->id_mitra);
        }

        // Filter Berdasarkan Tahun (Menggunakan kolom tgl_bantuan)
        if ($request->filled('tahun')) {
            $query->whereYear('tgl_pelaksanaan', $request->tahun);
        }

        $bantuans = $query->get();

        // Ambil daftar tahun unik dari database untuk isi dropdown filter
        $listTahun = KolaborasiBantuan::selectRaw('YEAR(tgl_pelaksanaan) as tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        // Ambil data pendukung untuk dropdown di modal tambah
        $mitras = Mitra::all();
        $kubes = Kube::all();
        

        $mitras = Mitra::all();
        $kubes = Kube::all();
        // Data mitra yang sedang difilter (untuk info di header)
        $filterMitra = $request->has('id_mitra') ? Mitra::find($request->id_mitra) : null;

        return view('admin.alur_bantuan.bantuan', compact('bantuans', 'mitras', 'kubes', 'filterMitra', 'listTahun'));
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
        $validated = $request->validate([
        'id_mitra'        => 'required',
        'id_kube'         => 'required',
        'jenis_bantuan'   => 'required',
        'nama_bantuan'    => 'required',
        'tgl_pelaksanaan' => 'required|date',
        'bantuan'         => 'required',
        'deskripsi'       => 'required',
        'foto_bukti'      => 'required|image|max:5120',
    ]);

    // Ambil semua data hasil validasi
    $data = $validated;

    // Proses upload foto (karena di form tipenya file, bukan teks)
    if ($request->hasFile('foto_bukti')) {
        $file = $request->file('foto_bukti');
        $nama_file = time() . "_" . $file->getClientOriginalName();
        $file->storeAs('bantuan', $nama_file, 'public');
        $data['foto_bukti'] = $nama_file; // Masukkan nama file ke array data
    }

    // Simpan ke database
    KolaborasiBantuan::create($data);

    return redirect()->back()->with('success', 'Data berhasil disimpan!');
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
    public function update(Request $request, $id)
    {
        $bantuan = KolaborasiBantuan::findOrFail($id);
        
        $data = $request->all();

        if ($request->hasFile('foto_bukti')) {
            // Hapus foto lama jika perlu, lalu upload yang baru
            $file = $request->file('foto_bukti');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->storeAs('public/bantuan', $nama_file);
            $data['foto_bukti'] = $nama_file;
        } else {
            // Jika tidak upload foto baru, pakai yang lama
            unset($data['foto_bukti']);
        }

        $bantuan->update($data);

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
    public function lihatFoto($filename)
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
