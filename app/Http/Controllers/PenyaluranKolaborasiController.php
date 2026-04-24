<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuktiPenyaluran;
use App\Models\DokumentasiPenyaluran;
use App\Models\KolaborasiBantuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PenyaluranKolaborasiController extends Controller
{
    /**
     * Menyimpan data bukti penyaluran dan banyak foto dokumentasi
     */
    public function store(Request $request, $id_kolaborasi)
    {
        // 1. Cek Duplikasi
        $isExist = BuktiPenyaluran::where('id_kolaborasi', $id_kolaborasi)->exists();
        if ($isExist) {
            return redirect()->back()->with('error', 'Data penyaluran sudah ada.');
        }

        // 2. Validasi Input
        $request->validate([
            'tgl_penyaluran'      => 'required|date',
            'catatan_pelaksanaan' => 'required|string|min:30',
            'foto'                => 'required|array', 
            'foto.*'              => 'image|mimes:jpeg,png,jpg|max:5120', 
        ], [
            'catatan_pelaksanaan.min' => 'Catatan pelaksanaan minimal harus 30 karakter.',
            'foto.required'           => 'Mohon unggah minimal satu foto dokumentasi.'
        ]);

        try {
            DB::beginTransaction();

            // 3. Simpan Data Bukti
            $bukti = BuktiPenyaluran::create([
                'id_kolaborasi'       => $id_kolaborasi,
                'tgl_penyaluran'      => $request->tgl_penyaluran,
                'catatan_pelaksanaan' => $request->catatan_pelaksanaan,
            ]);

            // 4. Proses Upload Multiple Foto (Gunakan logika yang berhasil tadi)
            if ($request->hasFile('foto')) {
                $tujuan_folder = public_path('uploads/dokumentasi');
                
                // Pastikan folder ada
                if (!file_exists($tujuan_folder)) {
                    mkdir($tujuan_folder, 0777, true);
                }

                foreach ($request->file('foto') as $file) {
                    $nama_foto = time() . "_" . $file->getClientOriginalName();
                    $path_asal = $file->getRealPath();
                    $path_tujuan = $tujuan_folder . DIRECTORY_SEPARATOR . $nama_foto;

                    // Gunakan move_uploaded_file agar tembus proteksi Windows/OneDrive
                    if (move_uploaded_file($path_asal, $path_tujuan)) {
                        DokumentasiPenyaluran::create([
                            'id_bukti'  => $bukti->id_bukti,
                            'foto_path' => $nama_foto
                        ]);
                    } else {
                        // Jika gagal, coba cara Laravel (backup)
                        $file->move($tujuan_folder, $nama_foto);
                        DokumentasiPenyaluran::create([
                            'id_bukti'  => $bukti->id_bukti,
                            'foto_path' => $nama_foto
                        ]);
                    }
                }
            }

            // 5. Update Status
            KolaborasiBantuan::where('id_kolaborasi', $id_kolaborasi)->update(['status' => 'Selesai']);

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil mengunggah bukti penyaluran!');

        } catch (\Exception $e) {
            DB::rollback();
            // DD di sini jika ingin melihat error aslinya saat development
            // dd($e->getMessage()); 
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
    public function destroy($id_kolaborasi)
    {
        try {
            DB::beginTransaction();

            // 1. Ambil data bukti
            $bukti = BuktiPenyaluran::where('id_kolaborasi', $id_kolaborasi)->first();

            if ($bukti) {
                // 2. Ambil semua dokumentasi terkait
                $dokumentasi = DokumentasiPenyaluran::where('id_bukti', $bukti->id_bukti)->get();

                // 3. Hapus file fisik dari folder public/uploads/dokumentasi
                foreach ($dokumentasi as $dok) {
                    $filePath = public_path('uploads/dokumentasi/' . $dok->foto_path);
                    if (File::exists($filePath)) {
                        File::delete($filePath);
                    }
                }

                // 4. Hapus data di database (Cascade delete jika sudah set di migrasi, 
                // jika belum maka hapus manual)
                DokumentasiPenyaluran::where('id_bukti', $bukti->id_bukti)->delete();
                $bukti->delete();
            }

            // 5. Kembalikan status bantuan menjadi 'Berjalan'
            KolaborasiBantuan::where('id_kolaborasi', $id_kolaborasi)->update(['status' => 'Berjalan']);

            DB::commit();
            return redirect()->back()->with('success', 'Data penyaluran berhasil dihapus. Status kembali menjadi Berjalan.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}