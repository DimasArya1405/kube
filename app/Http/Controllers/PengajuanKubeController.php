<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKube;
use App\Models\JenisBantuan;
use App\Models\Kube;
use App\Models\DetailPengajuan;
use Illuminate\Support\Facades\DB;

class PengajuanKubeController extends Controller
{
    // 🔹 FORM CREATE
    public function create()
    {
        $jenisBantuan = JenisBantuan::all();
        $kube = Kube::all();

        return view('admin.pengajuan_kube.create', compact('jenisBantuan', 'kube'));
    }

    // 🔹 STORE (FINAL MULTI ITEM)
    public function store(Request $request)
    {
        $request->validate([
            'id_kube' => 'required',
            'items'   => 'required'
        ]);

        DB::beginTransaction();

        try {

            $items = json_decode($request->items, true);

            // ✅ 1x pengajuan (HEADER)
            $pengajuan = PengajuanKube::create([
                'id_kube' => $request->id_kube,
                'tanggal_pengajuan' => now(),
                'status_pengajuan' => 'diajukan',
                'status_penerima' => 'menunggu'
            ]);

            // ❗ validasi tambahan (biar aman)
            if (!$items || count($items) == 0) {
                throw new \Exception('Item pengajuan kosong');
            }

            // ✅ loop detail (MULTI ITEM)
            foreach ($items as $item) {

                DetailPengajuan::create([
                    'pengajuan_id'      => $pengajuan->id_pengajuan_kube,
                    'id_jenis_bantuan'  => $item['id_jenis'],null, // 🔥 penting!
                    'nama_item'         => $item['nama'],
                    'jumlah'            => $item['jumlah']
                ]);
            }

            DB::commit();

            return redirect()->route('pengajuan.index')
                ->with('success', 'Pengajuan berhasil disimpan!');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors('Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // 🔹 INDEX (LIST)
    public function index()
    {
        $data = PengajuanKube::with('kube', 'detail.jenisBantuan')
                ->latest()
                ->get();

        return view('admin.pengajuan_kube.index', compact('data'));
    }
}