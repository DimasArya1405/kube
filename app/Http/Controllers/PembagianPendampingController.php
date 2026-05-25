<?php

namespace App\Http\Controllers;

use App\Models\PembagianPendamping;
use App\Models\Kube;
use App\Models\Pendamping; // Ganti jadi 'use App\Models\User;' kalau gabung di tabel user
use Illuminate\Http\Request;
use App\Exports\PembagianPendampingExport;
use Maatwebsite\Excel\Facades\Excel;

class PembagianPendampingController extends Controller
{
    public function index()
    {
        // Ambil data pembagian sekalian bawa relasi Kube dan Pendamping
        $pembagians = PembagianPendamping::with(['kube', 'pendamping'])->get();
        
        // Ambil data buat dropdown di Modal
        $kubes = Kube::orderBy('nama_kube', 'asc')->get();
        
        // Kalau pendamping ada di tabel users, pakainya: User::where('role', 'pendamping')->get();
        $pendampings = Pendamping::all(); 

        // Sesuai nama menu di sidebar Kak Yana (pembagian_pendamping)
        return view('admin.penugasan.pembagian_pendamping', compact('pembagians', 'kubes', 'pendampings'));
    }

    public function store(Request $request)
    {
        // Validasi inputan dari form
        $request->validate([
            'id_kube'       => 'required|integer',
            'id_pendamping' => 'required|integer',
            'tgl_pembagian' => 'required|date',
            'tgl_selesai'   => 'nullable|date|after_or_equal:tgl_pembagian', 
            'status'        => 'required|string',
        ], [
            'tgl_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai!'
        ]);

        // Simpan data ke database
        PembagianPendamping::create([
            'id_kube'       => $request->id_kube,
            'id_pendamping' => $request->id_pendamping,
            'tgl_pembagian' => $request->tgl_pembagian,
            'tgl_selesai'   => $request->tgl_selesai, // 🔥 Tangkap tgl_selesai di sini
            'status'        => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data Pembagian berhasil ditambahkan!');
    }

    public function tandaiSelesai($id)
    {
        $pembagian = PembagianPendamping::where('id_pembagian', $id)->firstOrFail();
        
        // Update tanggal selesai dengan tanggal hari ini dan ubah statusnya
        $pembagian->update([
            'tgl_selesai' => now()->format('Y-m-d'),
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'Penugasan pendamping berhasil diselesaikan!');
    }

    public function destroy($id)
    {
        $pembagian = PembagianPendamping::where('id_pembagian', $id)->firstOrFail();
        $pembagian->delete();

        return redirect()->back()->with('success', 'Data Pembagian berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new PembagianPendampingExport, 'Data_Pembagian_Pendamping.xlsx');
    }
}