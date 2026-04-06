<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KunjunganPendamping;
use App\Models\PembagianPendamping;


class KunjunganPendampingController extends Controller
{
    public function index()
    {
        // $kunjunganPendamping = KunjunganPendamping::with(['pembagian_pendamping'])->get();
        $kunjunganPendamping = KunjunganPendamping::with(['pembagian.pendamping','pembagian.kube'])->get();
        $pembagianPendamping = PembagianPendamping::all();
        
        return view('pendamping.dashboard.kunjungan_pendamping', compact('kunjunganPendamping', 'pembagianPendamping'));
    }
    public function create()
    {
        $pembagian = PembagianPendamping::with(['pendamping', 'kube'])->get();

        return view('pendamping.kunjungan.create', compact('pembagian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pembagian' => 'required',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required',
            'tujuan_kunjungan' => 'required',
            'kunjungan_ke' => 'required|integer',
        ]);

        KunjunganPendamping::create([
            'id_pembagian' => $request->id_pembagian,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'tujuan_kunjungan' => $request->tujuan_kunjungan,
            'kunjungan_ke' => $request->kunjungan_ke,
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function destroy($id)
    {
        KunjunganPendamping::findOrFail($id)->delete();

        return redirect()->back()->with('success','Data berhasil dihapus');
    }
}

