<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Kube;
use App\Models\Mitra;
use App\Models\Pendamping;
use App\Models\Koordinator;
use Illuminate\Http\Request;
use App\Exports\PelatihanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PelatihanController extends Controller
{
   public function index(Request $request)
{
    $search = $request->query('search');

    $pelatihans = Pelatihan::with(['kubes', 'mitra'])
        ->when($search, function ($query, $search) {
            return $query->where('nama_pelatihan', 'like', '%' . $search . '%')
                ->orWhereHas('kubes', function ($q) use ($search) {
                    $q->where('nama_kube', 'like', '%' . $search . '%');
                });
        })
        ->get();

    $kubes = Kube::all();
    $mitras = Mitra::all();

    // 1. Ambil data Pendamping
    $dataPendamping = Pendamping::all()->map(function($p) {
        return (object) [
            'id_gabungan' => 'pendamping_' . $p->id_pendamping,
            'nama' => $p->nama_pendamping, // Sesuaikan dengan nama field kamu
            'role' => 'Pendamping'
        ];
    });

    // 2. Ambil data Koordinator (beserta relasi user untuk ambil nama)
    $dataKoor = Koordinator::with('user')->get()->map(function($k) {
        return (object) [
            'id_gabungan' => 'koor_' . $k->id_koor,
            'nama' => $k->user->name ?? 'User Tidak Ditemukan', // Dari tabel users
            'role' => 'Koordinator'
        ];
    });

    // 3. Gabungkan keduanya
    $pengajars = $dataPendamping->concat($dataKoor);

    return view('admin.monevbimbingan.pelatihan', compact('pelatihans', 'kubes', 'mitras', 'pengajars'));
}

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string|max:150',
            'id_kube' => 'required|array', 
            'tanggal_mulai' => 'required|date',
            'status' => 'required'
        ]);

        $pelatihan = Pelatihan::create($request->except('id_kube'));

        if ($request->has('id_kube')) {
            $pelatihan->kubes()->attach($request->id_kube);
        }

        return redirect()->back()->with('success', 'Data pelatihan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        $pelatihan->update($request->except('id_kube'));

        if ($request->has('id_kube')) {
            $pelatihan->kubes()->sync($request->id_kube);
        } else {

            $pelatihan->kubes()->detach();
        }

        return redirect()->back()->with('success', 'Data pelatihan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        $pelatihan->kubes()->detach();

        $pelatihan->delete();

        return redirect()->back()->with('success', 'Data pelatihan berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new PelatihanExport, 'data-pelatihan.xlsx');
    }

    public function exportPdf()
    {
        // 'kubes', bukan 'kube'
        $pelatihans = Pelatihan::with(['kubes', 'pendamping'])->get();
        $pdf = Pdf::loadView('admin.monevbimbingan.pelatihan_pdf', compact('pelatihans'));
        return $pdf->download('data-pelatihan.pdf');
    }
}
