<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendamping;
use App\Models\Kecamatan;
use App\Models\User;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendampingExport;

class PendampingController extends Controller
{
    public function index()
    {
        $pendamping = Pendamping::with('kecamatan')->get();
        $kecamatan  = Kecamatan::all();

        return view('admin.data_master.pendamping', compact('pendamping', 'kecamatan'));
    }

    // Mengembalikan data JSON untuk modal detail
    public function show($id)
    {
        $item = Pendamping::with('kecamatan')->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_pendamping'), $namaFile);
            $data['foto'] = $namaFile;
        }

        Pendamping::create($data);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pendamping = Pendamping::findOrFail($id);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($pendamping->foto && file_exists(public_path('storage/foto_pendamping/' . $pendamping->foto))) {
                unlink(public_path('storage/foto_pendamping/' . $pendamping->foto));
            }

            $file     = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_pendamping'), $namaFile);
            $data['foto'] = $namaFile;
        }

        $pendamping->update($data);

        return redirect()->route('pendamping.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Pendamping::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function exportPdf()
{
    $pendamping = Pendamping::with('kecamatan')->get();

    $pdf = PDF::loadView('admin.data_master.pendamping_pdf', compact('pendamping'));

    return $pdf->download('data-pendamping.pdf');
}

    public function exportExcel()
{
    $pendamping = Pendamping::with('kecamatan')->get();

    $filename = "data-pendamping.csv";

    $handle = fopen($filename, 'w+');

    // =====================
    // JUDUL REPORT (BARIS ATAS)
    // =====================
    fputcsv($handle, ['DATA PENDAMPING KUBE']);
    fputcsv($handle, ['Dicetak: ' . now()->format('d-m-Y H:i')]);
    fputcsv($handle, []); // baris kosong

    // =====================
    // HEADER TABLE
    // =====================
    fputcsv($handle, [
        'No',
        'Nama Pendamping',
        'NIK',
        'Kecamatan',
        'No HP',
        'Status'
    ]);

    // =====================
    // DATA
    // =====================
    $no = 1;
    foreach ($pendamping as $item) {
        fputcsv($handle, [
            $no++,
            $item->nama_pendamping,
            $item->nik,
            $item->kecamatan->nama_kecamatan ?? '-',
            $item->no_hp,
            $item->status
        ]);
    }

    // =====================
    // FOOTER
    // =====================
    fputcsv($handle, []);
    fputcsv($handle, ['Total Data: ' . $pendamping->count()]);

    fclose($handle);

    return response()->download($filename)->deleteFileAfterSend(true);
}
}