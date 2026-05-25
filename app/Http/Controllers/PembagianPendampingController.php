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
        $request->validate([
            'id_kube' => 'required|integer',
            'id_pendamping' => 'required|integer',
            'tgl_pembagian' => 'required|date',
            'status' => 'required|string',
        ]);

        PembagianPendamping::create([
            'id_kube' => $request->id_kube,
            'id_pendamping' => $request->id_pendamping,
            'tgl_pembagian' => $request->tgl_pembagian,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data Pembagian berhasil ditambahkan!');
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