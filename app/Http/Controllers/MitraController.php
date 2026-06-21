<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Exports\MitraExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;



class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $statusFilter = request('status');
        $mitras = Mitra::withCount('bantuan_kolaborasi')->get();

        // Saring koleksi data setelah diambil dari database berdasarkan rumus di model
        if (!empty($statusFilter)) {
            $mitras = $mitras->filter(function ($mitra) use ($statusFilter) {
                return $mitra->status === $statusFilter;
            });
        }

        return view('admin.alur_bantuan.mitra', compact('mitras')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mitra.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan Data Baru
     */
    public function store(Request $request)
    {
        // Ambil semua data input
        $data = $request->all();

        if ($request->hasFile('mou')) {
            $file = $request->file('mou');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            
            // Gunakan path yang bersih untuk Windows
            $tujuan_folder = public_path('uploads/mou');

            // Step 1: Paksa buat folder jika belum ada dengan izin penuh
            if (!file_exists($tujuan_folder)) {
                mkdir($tujuan_folder, 0777, true);
            }

            // Step 2: Gunakan move_uploaded_file (Fungsi asli PHP) 
            // Ini lebih 'kuat' daripada $file->move() dalam menghadapi masalah folder lock
            $path_asal = $file->getRealPath();
            $path_tujuan = $tujuan_folder . DIRECTORY_SEPARATOR . $nama_file;

            if (move_uploaded_file($path_asal, $path_tujuan)) {
                $data['mou'] = $nama_file;
            } else {
                // Jika masih gagal, kita coba cara alternatif Laravel
                try {
                    $file->move($tujuan_folder, $nama_file);
                    $data['mou'] = $nama_file;
                } catch (\Exception $e) {
                    return back()->withError("Gagal upload file ke folder: " . $tujuan_folder . ". Pesan: " . $e->getMessage());
                }
            }
        }

        // Simpan ke database
        try {
            Mitra::create($data);
            return redirect()->route('mitra.index')->with('success', 'Data Mitra Berhasil Ditambahkan!');
        } catch (\Exception $e) {
            return back()->withError("Gagal simpan ke database: " . $e->getMessage());
        }
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
     * Ambil data untuk form Edit
     */
    public function edit(string $id)
    {
        $mitra = Mitra::findOrFail($id); // Mencari data mitra berdasarkan ID, jika tidak ada kirim error 404
        return response()->json($mitra); // Mengirim data dalam format JSON agar bisa ditangkap oleh JavaScript/Modal
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data mitra
     */
    public function update(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('mou')) {
            $file = $request->file('mou');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $tujuan_folder = public_path('uploads/mou');

            // 1. Pastikan folder ada (seperti kode store yang berhasil)
            if (!file_exists($tujuan_folder)) {
                mkdir($tujuan_folder, 0777, true);
            }

            // 2. Hapus file lama jika ada (agar tidak menumpuk)
            if ($mitra->mou && file_exists($tujuan_folder . DIRECTORY_SEPARATOR . $mitra->mou)) {
                unlink($tujuan_folder . DIRECTORY_SEPARATOR . $mitra->mou);
            }

            // 3. Gunakan cara 'paksa' move_uploaded_file
            $path_asal = $file->getRealPath();
            $path_tujuan = $tujuan_folder . DIRECTORY_SEPARATOR . $nama_file;

            if (move_uploaded_file($path_asal, $path_tujuan)) {
                $data['mou'] = $nama_file;
            } else {
                // Backup jika move_uploaded_file gagal (kadang di beberapa env Laravel butuh ini)
                $file->move($tujuan_folder, $nama_file);
                $data['mou'] = $nama_file;
            }
        } else {
            // Jika tidak ada file baru yang diunggah, tetap gunakan nama file lama
            $data['mou'] = $mitra->mou;
        }

        // 4. Update data ke database
        try {
            $mitra->update($data);
            return redirect()->route('mitra.index')->with('success', 'Data Mitra Berhasil Diperbarui!');
        } catch (\Exception $e) {
            return back()->withError("Gagal update database: " . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * Hapus Data Mitra
     */
    public function destroy($id)
    {
        $mitra = Mitra::findorFail($id);
        $mitra->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');

    }

    /**
     * FITUR VIEW PDF
     */
    public function viewPdf($id)
    {
        $mitra = Mitra::findOrFail($id);
        $pathFull = public_path('uploads/mou/' . $mitra->mou);

        if (!file_exists($pathFull)) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan di server.');
        }

        // 1. Ambil ekstensi file (pdf, jpg, png, dll)
        $extension = pathinfo($pathFull, PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        // 2. Tentukan Content-Type berdasarkan ekstensi
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png'
        ];

        $contentType = $mimeTypes[$extension] ?? 'application/octet-stream';

        // 3. Tampilkan file dengan header yang sesuai
        return response()->file($pathFull, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="'.$mitra->mou.'"'
        ]);
    }

    public function exportExcel()
    {
        return Excel::download(new MitraExport, 'daftar_mitra_'.time().'.xlsx');
    }

    public function exportPdf()
    {
        // Matikan error reporting Symfony sementara untuk menghindari bug isVirtual()
        try {
            $mitra = \App\Models\Mitra::all();
            
            // Gunakan view yang sangat simpel (Tanpa CSS dulu untuk tes)
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.alur_bantuan.mitra_pdf', [
                'mitra' => $mitra
            ]);

            return $pdf->download('Laporan_Mitra.pdf');
        } catch (\Throwable $e) {
            // Tampilkan error secara manual tanpa bantuan VarDumper Symfony
            echo "Ada kesalahan: " . $e->getMessage();
            exit;
        }
    }
    
}
