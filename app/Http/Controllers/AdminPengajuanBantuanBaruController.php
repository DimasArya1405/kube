<?php

namespace App\Http\Controllers;

use App\Models\DetailPengajuan;
use App\Models\JenisBantuan;
use App\Models\Kube;
use App\Models\PengajuanKube;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPengajuanBantuanBaruController extends Controller
{
    public function index()
    {
        $data = PengajuanKube::with(['kube', 'jenisBantuan', 'detail.jenisBantuan'])
            ->latest()
            ->get();

        return view('admin.pengajuan_bantuan_baru.index', compact('data'));
    }

    public function create()
    {
        $jenisBantuan = JenisBantuan::orderBy('jenis_bantuan')->get();
        $kube = Kube::orderBy('nama_kube')->get();

        return view('admin.pengajuan_bantuan_baru.create', compact('jenisBantuan', 'kube'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kube' => 'required|exists:kube,id_kube',
            'items' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $items = json_decode($request->items, true);

            if (!is_array($items) || count($items) === 0) {
                throw new \Exception('Item pengajuan kosong.');
            }

            foreach ($items as $item) {
                if (empty($item['id_jenis']) || empty($item['nama']) || empty($item['jumlah']) || (int) $item['jumlah'] <= 0) {
                    throw new \Exception('Jenis bantuan, nama item, dan jumlah wajib diisi.');
                }
            }

            foreach ($items as $item) {
                $pengajuan = PengajuanKube::create([
                    'id_kube' => $request->id_kube,
                    'id_user' => auth()->id(),
                    'id_jenis_bantuan' => $item['id_jenis'],
                    'jumlah_bantuan' => $item['jumlah'],
                    'tujuan_pengajuan' => $item['nama'],
                    'tanggal_pengajuan' => now(),
                    'status_pengajuan' => 'diajukan',
                    'status_penerima' => 'menunggu',
                ]);

                DetailPengajuan::create([
                    'pengajuan_id' => $pengajuan->id_pengajuan_kube,
                    'id_jenis_bantuan' => $item['id_jenis'],
                    'nama_item' => $item['nama'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            DB::commit();

            return redirect()->route('admin.pengajuan_bantuan_baru.index')
                ->with('success', 'Pengajuan bantuan baru berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors('Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
