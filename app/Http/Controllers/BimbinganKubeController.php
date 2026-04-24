<?php

namespace App\Http\Controllers;

use App\Models\BimbinganKube;
use App\Models\Kube;
use App\Models\Pendamping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BimbinganKubeController extends Controller
{
    public function index(Request $request)
    {
        $query = BimbinganKube::with(['kube', 'pendamping']);

        if ($request->from) {
            $query->whereDate('tanggal_bimbingan', '>=', $request->from);
        }

        if ($request->to) {
            $query->whereDate('tanggal_bimbingan', '<=', $request->to);
        }

        $datas = $query->latest()->get();

        $kubes = Kube::all();
        $pendampings = Pendamping::all(); // ✅ TAMBAHAN

        return view('admin.bimbingan_kube.index', compact('datas', 'kubes', 'pendampings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required',
            'id_pendamping' => 'required',
            'id_kube' => 'required',
            'jenis_bimbingan' => 'required',
            'materi_bimbingan' => 'required',
            'tanggal_bimbingan' => 'required|date',
            'status_bimbingan' => 'required',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bimbingan', $filename, 'public');
            $data['lampiran'] = $path;
        }

        BimbinganKube::create($data);

        return redirect()->route('bimbingan.index')->with('success', 'Data bimbingan berhasil disimpan!');
    }

    public function edit($id)
    {
        $bimbingan = BimbinganKube::findOrFail($id);
        $kubes = Kube::all();
        $pendampings = Pendamping::all();

        return view('admin.bimbingan_kube.edit', compact('bimbingan', 'kubes', 'pendampings'));
    }

    public function update(Request $request, $id)
    {
        $bimbingan = BimbinganKube::findOrFail($id);

        $request->validate([
            'id_kube' => 'required',
            'id_pendamping' => 'required',
            'jenis_bimbingan' => 'required',
            'materi_bimbingan' => 'required',
            'tanggal_bimbingan' => 'required|date',
            'status_bimbingan' => 'required',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('lampiran')) {
            if ($bimbingan->lampiran) {
                Storage::disk('public')->delete($bimbingan->lampiran);
            }

            $data['lampiran'] = $request->file('lampiran')->store('bimbingan', 'public');
        }

        $bimbingan->update($data);

        return redirect()->route('bimbingan.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $bimbingan = BimbinganKube::findOrFail($id);

        if ($bimbingan->lampiran) {
            Storage::disk('public')->delete($bimbingan->lampiran);
        }

        $bimbingan->delete();

        return redirect()->route('bimbingan.index')->with('success', 'Data berhasil dihapus!');
    }
}