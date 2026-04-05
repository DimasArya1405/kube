<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Koordinator;
use App\Models\Kecamatan;

class KoordinatorController extends Controller
{
    public function index()
    {
        $koordinator = Koordinator::with('kecamatan')->get();
        $kecamatan = Kecamatan::all();

        return view('admin.data_master.koordinator', compact('koordinator','kecamatan'));
    }

    public function store(Request $request)
    {

    $data = $request->all();

    if($request->hasFile('foto')){

    $file = $request->file('foto');
    $namaFile = time().'_'.$file->getClientOriginalName();
    $file->move(public_path('storage/foto_koordinator'), $namaFile);

    $data['foto'] = $namaFile;

    }

    Koordinator::create($data);

    return redirect()->back()->with('success','Data berhasil ditambahkan');

    }

    public function destroy($id)
    {
        Koordinator::findOrFail($id)->delete();

        return redirect()->back()->with('success','Data berhasil dihapus');
    }
}