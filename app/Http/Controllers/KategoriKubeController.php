<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriKube;

class KategoriKubeController extends Controller
{
    // TAMPIL DATA
    public function index()
    {
        $data = KategoriKube::all();
        return view('admin.data_master.kategori_kube', compact('data'));
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        KategoriKube::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status
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
        'status' => $request->status
    ]);

    return redirect()->back()->with('success', 'Data berhasil diupdate');
    }
    // HAPUS DATA
    public function destroy($id)
    {
        KategoriKube::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

}
