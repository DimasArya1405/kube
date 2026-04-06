<?php

namespace App\Http\Controllers;

use App\Models\JenisBantuan;
use Illuminate\Http\Request;

class JenisBantuanController extends Controller
{
    public function index()
    {
        $jenis_bantuan = JenisBantuan::all();
        return view('admin.alur_bantuan.jenis_bantuan', compact('jenis_bantuan'));
    }
    public function tambah(Request $request)
    {
        $jenis_bantuan = new JenisBantuan;
        $jenis_bantuan->jenis_bantuan = $request->jenis_bantuan;
        $jenis_bantuan->save();
        return redirect()->route('admin.alur_bantuan.jenis_bantuan.index')->with('success', 'Data berhasil ditambahkan');
    }
}
