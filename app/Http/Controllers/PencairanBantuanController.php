<?php

namespace App\Http\Controllers;

use App\Models\PencairanBantuan;
use Illuminate\Http\Request;

class PencairanBantuanController extends Controller
{
    public function index()
    {
        $pencairan_bantuan = PencairanBantuan::with('pengajuan_kube.kube', 'jenis_bantuan')->get();
        return view('admin.alur_bantuan.pencairan_bantuan', compact('pencairan_bantuan'));
    }
}
