<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KunjunganPendamping;
use App\Models\PembagianPendamping;
use Illuminate\Support\Facades\Storage;


class KunjunganPendampingController extends Controller
{
    public function index()
    {
        $kunjunganPendamping = KunjunganPendamping::with([
            'pembagian.pendamping',
            'pembagian.kube'
        ])->get();

        $pembagianPendamping = PembagianPendamping::with([
            'pendamping',
            'kube'
        ])->get();

        $pendamping = $pembagianPendamping->groupBy('id_pendamping');

        // Tambahkan ini
        $dataPembagian = $pembagianPendamping->map(function($item) {
            return [
                'id_pembagian'  => $item->id_pembagian,
                'id_pendamping' => $item->id_pendamping,
                'kube'          => ['nama_kube' => $item->kube->nama_kube ?? ''],
            ];
        })->values();

        return view('pendamping.dashboard.kunjungan_pendamping', compact(
            'kunjunganPendamping',
            'pembagianPendamping',
            'pendamping',
            'dataPembagian' 
        ));
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
            'catatan'
        ]);

        KunjunganPendamping::create([
            'id_pembagian' => $request->id_pembagian,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'tujuan_kunjungan' => $request->tujuan_kunjungan,
            'kunjungan_ke' => $request->kunjungan_ke,
            'catatan' => $request->catatan
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function edit($id)
    {
        $kunjunganPendamping = KunjunganPendamping::with(['pembagian.pendamping','pembagian.kube'])->get();

        $kunjungan = KunjunganPendamping::with('pembagian.kube')->findOrFail($id);

        $pembagianPendamping = PembagianPendamping::with(['pendamping','kube'])->get();

        return view('pendamping.dashboard.kunjungan_pendamping', compact(
            'kunjunganPendamping',
            'kunjungan',
            'pembagianPendamping'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pembagian' => 'required|exists:pembagian_pendamping,id_pembagian',
            'tanggal_kunjungan' => 'required|date',
            'waktu_kunjungan' => 'required',
            'tujuan_kunjungan' => 'required|in:Monitoring,Evaluasi,Koordinasi,Kunjungan Rutin',
            'kunjungan_ke' => 'required|integer',
            'catatan' 
        ]);

        $kunjungan = KunjunganPendamping::findOrFail($id);

        $kunjungan->update([
            'id_pembagian' => $request->id_pembagian,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'waktu_kunjungan' => $request->waktu_kunjungan,
            'tujuan_kunjungan' => $request->tujuan_kunjungan,
            'kunjungan_ke' => $request->kunjungan_ke,
            'catatan' => $request->catatan
        ]);

        return redirect()->back()->with('success','Data berhasil diupdate');
    }

    public function show($id)
    {
        $kunjungan = KunjunganPendamping::with([
            'pembagian.pendamping',
            'pembagian.kube'
        ])->findOrFail($id);

        return view('pendamping.kunjungan.detail', compact('kunjungan'));
    }

    public function destroy($id)
    {
        KunjunganPendamping::findOrFail($id)->delete();

        return redirect()->back()->with('success','Data berhasil dihapus');
    }

    public function selesai(Request $request, $id)
{
    $request->validate([
        'foto_bukti' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'catatan_hasil' => 'nullable|string'
    ]);

    $kunjungan = KunjunganPendamping::findOrFail($id);

    // upload file
    $path = $request->file('foto_bukti')->store('bukti_kunjungan', 'public');

    $kunjungan->update([
        'status' => 'selesai',
        'foto_bukti' => $path,
        'catatan_hasil' => $request->catatan_hasil
    ]);

    return redirect()->back()->with('success', 'Kunjungan selesai');
}
}

