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
        // Validasi input pastikan id_kube adalah array
        $request->validate([
            'id_kube' => 'required|array',
            'id_pendamping' => 'required',
            'tgl_pembagian' => 'required|date',
            // ... validasi lainnya
        ]);

        // Looping data KUBE untuk disimpan ke database
        foreach ($request->id_kube as $kubeId) {
            PembagianPendamping::create([
                'id_kube' => $kubeId,
                'id_pendamping' => $request->id_pendamping,
                'tgl_pembagian' => $request->tgl_pembagian,
                'tgl_selesai' => $request->tgl_selesai,
                'status' => $request->status,
            ]);
        }

        return redirect()->back()->with('success', 'Data pembagian berhasil ditambahkan!');
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
