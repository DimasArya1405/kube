<?php

namespace App\Http\Controllers;

use App\Models\BimbinganKube;
use App\Models\Kube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BimbinganKubeController extends Controller
{
    // ✅ TAMPIL DATA
    public function index(Request $request)
    {
        $query = BimbinganKube::with(['kube', 'pendamping']);

        // 🔥 FILTER TANGGAL
        if ($request->from) {
            $query->whereDate('tanggal_bimbingan', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('tanggal_bimbingan', '<=', $request->to);
        }

        $datas = $query->latest()->get();

        $kubes = Kube::all();

        return view('admin.bimbingan_kube.index', compact('datas', 'kubes'));
    }

    // ✅ SIMPAN DATA
    public function store(Request $request)
{
    $request->validate([
        'id_jadwal' => 'required',
        'id_kube' => 'required',
        'id_pendamping' => 'required', // tambahin ini
        'jenis_bimbingan' => 'required',
        'materi_bimbingan' => 'required',
        'tanggal_bimbingan' => 'required|date',
        'status_bimbingan' => 'required',
        'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
    ]);

    $data = $request->all();

    // ❌ HAPUS INI
    // $data['id_pendamping'] = Auth::user()->id_pendamping;

    if ($request->hasFile('lampiran')) {
        $file = $request->file('lampiran');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('bimbingan', $filename, 'public');
        $data['lampiran'] = $path;
    }

    BimbinganKube::create($data);

    return redirect()->route('bimbingan.index')
        ->with('success', 'Data bimbingan berhasil disimpan!');
}
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'id_jadwal' => 'required',
    //         'id_kube' => 'required',
    //         'jenis_bimbingan' => 'required',
    //         'materi_bimbingan' => 'required',
    //         'tanggal_bimbingan' => 'required|date',
    //         'status_bimbingan' => 'required',
    //         'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
    //     ]);

    //     $data = $request->all();

    //     // 🔥 AUTO ISI PENDAMPING DARI LOGIN
    //     $data['id_pendamping'] = Auth::user()->id_pendamping;

    //     // 🔥 UPLOAD FILE
    //     if ($request->hasFile('lampiran')) {
    //         $file = $request->file('lampiran');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $path = $file->storeAs('bimbingan', $filename, 'public');
    //         $data['lampiran'] = $path;
    //     }

    //     BimbinganKube::create($data);

    //     return redirect()->route('bimbingan.index')
    //         ->with('success', 'Data bimbingan berhasil disimpan!');
    // }

    // ✅ FORM EDIT
    public function edit($id)
    {
        $bimbingan = BimbinganKube::findOrFail($id);
        $kubes = Kube::all();

        return view('admin.bimbingan_kube.edit', compact('bimbingan', 'kubes'));
    }

    // ✅ UPDATE DATA
    public function update(Request $request, $id)
    {
        $bimbingan = BimbinganKube::findOrFail($id);

        $request->validate([
            'id_kube' => 'required',
            'jenis_bimbingan' => 'required',
            'materi_bimbingan' => 'required',
            'tanggal_bimbingan' => 'required|date',
            'status_bimbingan' => 'required',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $data = $request->all();

        // 🔥 PASTIKAN PENDAMPING TETAP DARI LOGIN
        // $data['id_pendamping'] = Auth::user()->id_pendamping;

        // 🔥 HANDLE FILE
        if ($request->hasFile('lampiran')) {

            // hapus file lama
            if ($bimbingan->lampiran) {
                Storage::disk('public')->delete($bimbingan->lampiran);
            }

            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bimbingan', $filename, 'public');
            $data['lampiran'] = $path;
        }

        $bimbingan->update($data);

        return redirect()->route('bimbingan.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    // ✅ HAPUS DATA
    public function destroy($id)
    {
        $bimbingan = BimbinganKube::findOrFail($id);

        // hapus file jika ada
        if ($bimbingan->lampiran) {
            Storage::disk('public')->delete($bimbingan->lampiran);
        }

        $bimbingan->delete();

        return redirect()->route('bimbingan.index')
            ->with('success', 'Data berhasil dihapus!');
    }
    public function pdf(Request $request)
{
    $query = BimbinganKube::with(['kube', 'pendamping']);

    // FILTER TANGGAL
    if ($request->from) {
        $query->whereDate('tanggal_bimbingan', '>=', $request->from);
    }

    if ($request->to) {
        $query->whereDate('tanggal_bimbingan', '<=', $request->to);
    }

    $datas = $query->latest()->get();

    $pdf = Pdf::loadView('admin.bimbingan_kube.pdf', compact('datas'));

    return $pdf->download('laporan_bimbingan_kube.pdf');
}
}