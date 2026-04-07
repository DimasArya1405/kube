<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembagianKoordinator;
use App\Models\Koordinator;
use App\Models\PembagianPendamping;

class PembagianKoordinatorController extends Controller
{
    public function index()
    {
        // DATA + RELASI
        $data = PembagianKoordinator::with([
            'koordinator',
            'pembagianPendamping.pendamping',
            'pembagianPendamping.kube'
        ])->get();

        // DROPDOWN KOORDINATOR
        $koor = Koordinator::all();

        // DROPDOWN PENDAMPING + KUBE
        $pendamping = PembagianPendamping::join('pendamping', 'pembagian_pendamping.id_pendamping', '=', 'pendamping.id_pendamping')
            ->join('kube', 'pembagian_pendamping.id_kube', '=', 'kube.id_kube')
            ->select(
                'pembagian_pendamping.id_pembagian',
                'pendamping.nama_pendamping',
                'kube.nama_kube'
            )
            ->get();

        return view('admin.penugasan.pembagian_koordinator', compact('data', 'koor', 'pendamping'));
    }

    // ✅ SIMPAN
    public function store(Request $request)
    {
        PembagianKoordinator::create([
            'id_koor' => $request->id_koor,
            'id_pembagian' => $request->id_pembagian,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $data = PembagianKoordinator::findOrFail($id);

        $data->update([
            'id_koor' => $request->id_koor,
            'id_pembagian' => $request->id_pembagian,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    // ✅ DELETE
    public function destroy($id)
    {
        $data = PembagianKoordinator::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}