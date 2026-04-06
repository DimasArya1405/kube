<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Kube; // Import model pendukung untuk dropdown
use App\Models\Mitra; // Import model pendukung untuk dropdown
use App\Models\Pendamping; // Import model pendukung untuk dropdown
use Illuminate\Http\Request;

class PelatihanController extends Controller
{
    public function index()
    {
        $pelatihans = Pelatihan::all();        // Ambil data untuk dropdown di modal
        $kubes = Kube::all(); 
        $pendampings = Pendamping::all(); 
        $mitras = Mitra::all(); 
        return view('admin.pelatihan.index', compact('pelatihans', 'kubes', 'pendampings', 'mitras'));
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
}