<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Koordinator;
use App\Models\Kecamatan;
use App\Models\User;
use App\Exports\KoordinatorExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KoordinatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Koordinator::with(['user', 'kecamatan']);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $koordinator = $query->get();

        // Hanya tampilkan user role koordinator yang BELUM ada di tabel koordinator
        $sudahAdaKoor = Koordinator::pluck('id_user')->toArray();
        $users = User::where('role', 'koordinator')
                     ->whereNotIn('id_user', $sudahAdaKoor)
                     ->get();

        $kecamatan = Kecamatan::all();

        return view('admin.data_master.koordinator', compact('koordinator', 'users', 'kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user'      => 'required|exists:users,id_user',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
        ]);

        Koordinator::create([
            'id_user'      => $request->id_user,
            'id_kecamatan' => $request->id_kecamatan,
            'status'       => 'non-aktif', // otomatis non-aktif, aktif ketika diberi tugas di fitur pembagian
        ]);

        return redirect()->back()->with('success', 'Data koordinator berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'status'       => 'required|in:aktif,non-aktif',
        ]);

        Koordinator::findOrFail($id)->update([
            'id_kecamatan' => $request->id_kecamatan,
            'status'       => $request->status,
        ]);

        return redirect()->back()->with('success', 'Data koordinator berhasil diupdate');
    }

    public function destroy($id)
    {
        Koordinator::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data koordinator berhasil dihapus');
    }

    public function exportPdf(Request $request)
    {
        $query = Koordinator::with(['user', 'kecamatan']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $koordinator = $query->get();
        $filterStatus = $request->status;

        $pdf = Pdf::loadView('admin.data_master.koordinator_pdf', compact('koordinator', 'filterStatus'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('data-koordinator-' . date('d-m-Y') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $filterStatus = $request->status;
        return Excel::download(
            new KoordinatorExport($filterStatus),
            'data-koordinator-' . date('d-m-Y') . '.xlsx'
        );
    }
}
