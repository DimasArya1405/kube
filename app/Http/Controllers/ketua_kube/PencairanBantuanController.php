<?php

namespace App\Http\Controllers\ketua_kube;

use App\Http\Controllers\Controller;
use App\Models\Kube;
use App\Models\PencairanBantuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PencairanBantuanController extends Controller
{
    public function index()
    {
        $kube = Kube::where('id_user', Auth::id())
            ->firstOrFail();

        $pencairanBantuan = PencairanBantuan::with([
            'pengajuan_kube.jenisBantuan'
        ])
            ->whereHas('pengajuan_kube', function ($query) use ($kube) {
                $query->where('id_kube', $kube->id_kube);
            })
            ->get();
        return view('ketua_kube.pengajuan_bantuan.pencairan_bantuan', compact(
            'kube',
            'pencairanBantuan'
        ));
    }
}
