<?php

namespace App\Http\Controllers;

use App\Models\Pendamping;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PendampingExport;

class PendampingController extends Controller
{
    public function index()
    {
        $pendamping = Pendamping::with('kecamatan')->get();
        $kecamatan = Kecamatan::all();

        return view('admin.data_master.pendamping', compact('pendamping','kecamatan'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if($request->hasFile('foto')){
            $file = $request->file('foto');
            $namaFile = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('storage/foto_pendamping'), $namaFile);
            $data['foto'] = $namaFile;
        }

        Pendamping::create($data);

        return redirect()->back()->with('success','Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Pendamping::findOrFail($id)->delete();
        return redirect()->back()->with('success','Data berhasil dihapus');
    }

    public function exportPdf()
    {
        $pendamping = Pendamping::all();
        $pdf = PDF::loadView('admin.data_master.pendamping_pdf', compact('pendamping'));
        return $pdf->download('data_pendamping.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PendampingExport, 'data_pendamping.xlsx');
    }
}