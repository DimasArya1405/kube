<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PembagianKoordinator;
use App\Models\Koordinator;
use App\Models\PembagianPendamping;
use App\Models\Kecamatan;
use Carbon\Carbon;

class PembagianKoordinatorController extends Controller
{
    public function index()
    {
        $data = PembagianKoordinator::with([
            'koordinator',
            'pembagianPendamping.pendamping',
            'pembagianPendamping.kube'
        ])->latest()->get();

        // AUTO STATUS
        foreach ($data as $d) {
            $d->status = ($d->tgl_selesai && Carbon::parse($d->tgl_selesai)->isPast())
                ? 'Selesai' : 'Aktif';
        }

        // GROUP
        $data = $data->groupBy('id_koor');

        $koor = Koordinator::all();
        $kecamatan = Kecamatan::all();
        $pendamping = [];

        return view('admin.penugasan.pembagian_koordinator', compact(
            'data','koor','pendamping','kecamatan'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_koor' => 'required',
            'id_pembagian' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'nullable|date|after_or_equal:tgl_mulai',
        ]);

        PembagianKoordinator::create($request->all());

        return back()->with('success','Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_koor' => 'required',
            'id_pembagian' => 'required',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai'
        ]);

        $data = PembagianKoordinator::findOrFail($id);
        $data->update($request->all());

        return back()->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        PembagianKoordinator::findOrFail($id)->delete();
        return back()->with('success','Data dihapus');
    }

    public function getPendamping($id_kecamatan, $selected = null)
{
    $sudahDipakai = PembagianKoordinator::pluck('id_pembagian')->toArray();

    // hapus id yang sedang diedit
    if($selected){
        $sudahDipakai = array_diff($sudahDipakai, [$selected]);
        }

        $data = PembagianPendamping::join(
                'pendamping',
                'pembagian_pendamping.id_pendamping',
                '=',
                'pendamping.id_pendamping'
            )
            ->join(
                'kube',
                'pembagian_pendamping.id_kube',
                '=',
                'kube.id_kube'
            )
            ->where('pendamping.id_kecamatan', $id_kecamatan)
            ->whereNotIn('pembagian_pendamping.id_pembagian', $sudahDipakai)
            ->select(
                'pembagian_pendamping.id_pembagian',
                'pendamping.nama_pendamping',
                'kube.nama_kube'
            )
            ->get();

        return response()->json($data);
    }

    // PDF
    public function exportPDF()
{
    $data = PembagianKoordinator::with([
        'koordinator',
        'pembagianPendamping.pendamping',
        'pembagianPendamping.kube'
    ])->get();

    // ✅ TAMBAHAN (biar status muncul)
    foreach ($data as $d) {
        $d->status = ($d->tgl_selesai && Carbon::parse($d->tgl_selesai)->isPast())
            ? 'Selesai' : 'Aktif';
    }

    $data = $data->groupBy('id_koor');

    return Pdf::loadView('admin.penugasan.pembagian_koordinator_pdf', [
        'data' => $data,
        'isPdf' => true
    ])->download('laporan.pdf');
}

public function exportExcel()
{
    $data = PembagianKoordinator::with([
        'koordinator',
        'pembagianPendamping.pendamping',
        'pembagianPendamping.kube'
    ])->get();

    $filename = "laporan_pembagian_koordinator.xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");

    echo "<table border='1'>";
    echo "<tr>
            <th>No</th>
            <th>Koordinator</th>
            <th>Pendamping</th>
            <th>KUBE</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Status</th>
          </tr>";

    $no = 1;

    foreach ($data as $row) {

        $pp = $row->pembagianPendamping;

        $status = ($row->tgl_selesai && \Carbon\Carbon::parse($row->tgl_selesai)->isPast())
            ? 'Selesai' : 'Aktif';

        echo "<tr>
                <td>{$no}</td>
                <td>".($row->koordinator->nama_koor ?? '-')."</td>
                <td>".($pp->pendamping->nama_pendamping ?? '-')."</td>
                <td>".($pp->kube->nama_kube ?? '-')."</td>
                <td>".($row->tgl_mulai ?? '-')."</td>
                <td>".($row->tgl_selesai ?? '-')."</td>
                <td>{$status}</td>
              </tr>";

        $no++;
    }

    echo "</table>";
    exit;
}
}