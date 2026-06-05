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
        $query = Koordinator::with(['user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $koordinator = $query->get();

        $sudahAdaKoor = Koordinator::pluck('id_user')->toArray();
        $users = User::where('role', 'koordinator')
                    ->whereNotIn('id_user', $sudahAdaKoor)
                    ->get();

        return view('admin.data_master.koordinator', compact('koordinator', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user'       => 'required|exists:users,id_user',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_koordinator'), $namaFile);
            $fotoPath = 'foto_koordinator/' . $namaFile;
        }

        Koordinator::create([
            'id_user'       => $request->id_user,
            'foto'          => $fotoPath,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status'        => 'non-aktif',
        ]);

        return redirect()->back()->with('success', 'Data koordinator berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:aktif,non-aktif',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'foto'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $koor = Koordinator::findOrFail($id);

        $fotoPath = $koor->foto; // default tetap foto lama
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($koor->foto) {
                $filePath = public_path('storage/' . $koor->foto);
                if (file_exists($filePath)) unlink($filePath);
            }
            $file     = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_koordinator'), $namaFile);
            $fotoPath = 'foto_koordinator/' . $namaFile;
        }

        $koor->update([
            'status'        => $request->status,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'foto'          => $fotoPath,
        ]);

        return redirect()->back()->with('success', 'Data koordinator berhasil diupdate');
    }

    public function destroy($id)
    {
        $koor = Koordinator::findOrFail($id);

        if ($koor->foto) {
            $filePath = public_path('storage/' . $koor->foto);
            if (file_exists($filePath)) unlink($filePath);
        }

        $koor->delete();

        return redirect()->back()->with('success', 'Data koordinator berhasil dihapus');
    }

    public function exportPdf(Request $request)
    {
        $query = Koordinator::with(['user']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $koordinator  = $query->get();

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