<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Kube; 
use App\Models\Mitra; 
use App\Models\Pendamping; 
use Illuminate\Http\Request;
use App\Exports\PelatihanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PelatihanController extends Controller
{
    public function index(Request $request) 
    {
    $search = $request->query('search');
    $pelatihans = Pelatihan::with(['kube', 'pendamping', 'mitra'])
        ->when($search, function ($query, $search) {
            return $query->where('nama_pelatihan', 'like', '%' . $search . '%')
                         ->orWhereHas('kube', function ($q) use ($search) {
                             $q->where('nama_kube', 'like', '%' . $search . '%');
                         })
                         ->orWhereHas('pendamping', function ($q) use ($search) {
                             $q->where('nama_pendamping', 'like', '%' . $search . '%');
                         });
        })
        ->get();

    $kubes = \App\Models\Kube::all();
    $pendampings = \App\Models\Pendamping::all();
    $mitras = \App\Models\Mitra::all();
    return view('admin.monevbimbingan.pelatihan', compact('pelatihans', 'kubes', 'pendampings', 'mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string|max:150',
            'id_kube' => 'required',
            'tanggal_mulai' => 'required|date',
            'status' => 'required'
        ]);

        Pelatihan::create($request->all());

        return redirect()->back()->with('success', 'Data pelatihan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $pelatihan->update($request->all());
        return redirect()->back()->with('success', 'Data pelatihan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);
        $pelatihan->delete();
        return redirect()->back()->with('success', 'Data pelatihan berhasil dihapus!');
    }

    public function exportExcel() 
    {
        return Excel::download(new PelatihanExport, 'data-pelatihan.xlsx');
    }

    public function exportPdf() 
    {
        $pelatihans = Pelatihan::with(['kube', 'pendamping'])->get();
        $pdf = Pdf::loadView('admin.monevbimbingan.pelatihan_pdf', compact('pelatihans'));
        return $pdf->download('data-pelatihan.pdf');
    }
}