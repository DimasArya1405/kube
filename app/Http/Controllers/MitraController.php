<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mitra;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mitras = Mitra::all();
        return view('admin.alur_bantuan.mitra',compact('mitras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mitra.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra'   => 'required',
            'jenis_mitra'  => 'required',
            'no_telp'      => 'required',
            'email'        => 'required|email',
            'nama_pic'     => 'required',
            'telp_pic'     => 'required',
            'mou'          => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'tgl_mou'      => 'required|date',
            'masa_berlaku' => 'required|numeric',
            'alamat'       => 'required',
        ]);
        $data = $request->all();
        if  ($request->hasFile('mou')){
            $file = $request->file('mou');
            $nama_file = time()."_".$file->getClientOriginalName();
            $file->storeAs('public/mou', $nama_file);
            $data['mou'] = $nama_file;
        }
        Mitra::create($data);
        return redirect()->back()->with('success','Mitra berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $mitra = Mitra::findorFail($id);
        $mitra->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');

    }
}
