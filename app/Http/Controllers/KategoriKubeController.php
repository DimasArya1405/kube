<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriKube;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KategoriExport;

class KategoriKubeController extends Controller
{
    // TAMPIL DATA
    public function index(Request $request)
{
    $search = $request->search;

    $data = KategoriKube::where('nama_kategori', 'LIKE', "%$search%")
                    ->get();

    return view('admin.data_master.kategori_kube', compact('data'));
}
    
    // SIMPAN DATA
    public function store(Request $request)
    {
        KategoriKube::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            // 'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }
    //edit
    public function edit($id)
{
    $data = KategoriKube::all(); // ini buat tabel
    $editData = KategoriKube::findOrFail($id); // ini buat form edit

    return view('admin.data_master.kategori_kube', compact('data', 'editData'));
}
    // UPDATE DATA
    public function update(Request $request, $id)
{
    $data = KategoriKube::find($id);

    $data->update([
        'nama_kategori' => $request->nama_kategori,
        'deskripsi' => $request->deskripsi,
        // 'status' => $request->status
    ]);
    
    return redirect()->back()->with('success', 'Data berhasil diupdate');
    }
    //Detail
            public function show($id)
    {
        $data = KategoriKube::findOrFail($id);
        return view('admin.data_master.detail_kategori', compact('data'));
    }
    // HAPUS DATA
    public function destroy($id)
    {
        KategoriKube::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
    
    public function exportPdf()
{
    $kategori = KategoriKube::all();

    $pdf = Pdf::loadView(
        'admin.data_master.kategori_pdf',
        compact('kategori')
    );

    return $pdf->download('kategori-kube.pdf');
}
    public function exportExcel()
    {
    return Excel::download(new KategoriExport, 'kategori-kube.xlsx');
    }
}
