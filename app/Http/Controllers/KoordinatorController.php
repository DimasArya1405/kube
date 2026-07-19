<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Koordinator;
use App\Models\User;
use App\Models\Kecamatan;
use App\Exports\KoordinatorExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KoordinatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Koordinator::with(['user', 'kecamatan', 'desa']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $koordinator = $query->get();

        $sudahAdaKoor = Koordinator::pluck('id_user')->toArray();

        // Hanya user dengan role koordinator DAN status akun aktif yang boleh ditambahkan
        $users = User::where('role', 'koordinator')
            ->where('status', 'aktif')
            ->whereNotIn('id_user', $sudahAdaKoor)
            ->get();

        $kecamatan = Kecamatan::all();

        return view('admin.data_master.koordinator', compact('koordinator', 'users', 'kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user'             => 'required|exists:users,id_user',
            'nik'                 => 'required|max:16',
            'nama_koordinator'    => 'required|max:100',
            'jenis_kelamin'       => 'required|in:L,P',
            'tempat_lahir'        => 'required|max:50',
            'tanggal_lahir'       => 'required|date',
            'alamat'              => 'required',
            'no_hp'               => 'required|max:15',
            'email'               => 'required|email|max:100',
            'pendidikan_terakhir' => 'required|max:50',
            'id_kecamatan'        => 'required|exists:kecamatan,id_kecamatan',
            'id_desa_kelurahan'   => 'nullable|exists:desa_kelurahan,id_desa_kelurahan',
            'wilayah'             => 'nullable|max:100',
            'status'              => 'required|in:Aktif,Tidak Aktif',
            'foto'                => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $koordinator = new Koordinator;
        $koordinator->id_user             = $request->id_user;
        $koordinator->nik                 = $request->nik;
        $koordinator->nama_koordinator    = $request->nama_koordinator;
        $koordinator->jenis_kelamin       = $request->jenis_kelamin;
        $koordinator->tempat_lahir        = $request->tempat_lahir;
        $koordinator->tanggal_lahir       = $request->tanggal_lahir;
        $koordinator->alamat              = $request->alamat;
        $koordinator->no_hp               = $request->no_hp;
        $koordinator->email               = $request->email;
        $koordinator->pendidikan_terakhir = $request->pendidikan_terakhir;
        $koordinator->id_kecamatan        = $request->id_kecamatan;
        $koordinator->id_desa_kelurahan   = $request->id_desa_kelurahan;
        $koordinator->wilayah             = $request->wilayah;
        $koordinator->status              = $request->status;

        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_koordinator'), $namaFile);
            $koordinator->foto = 'foto_koordinator/' . $namaFile;
        }

        $koordinator->save();

        return back()->with('success', 'Data koordinator berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik'                 => 'required|max:16',
            'nama_koordinator'    => 'required|max:100',
            'jenis_kelamin'       => 'required|in:L,P',
            'tempat_lahir'        => 'required|max:50',
            'tanggal_lahir'       => 'required|date',
            'alamat'              => 'required',
            'no_hp'               => 'required|max:15',
            'email'               => 'required|email|max:100',
            'pendidikan_terakhir' => 'required|max:50',
            'id_kecamatan'        => 'required|exists:kecamatan,id_kecamatan',
            'id_desa_kelurahan'   => 'nullable|exists:desa_kelurahan,id_desa_kelurahan',
            'wilayah'             => 'nullable|max:100',
            'status'              => 'required|in:Aktif,Tidak Aktif',
            'foto'                => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $koordinator = Koordinator::findOrFail($id);

        $koordinator->nik                 = $request->nik;
        $koordinator->nama_koordinator    = $request->nama_koordinator;
        $koordinator->jenis_kelamin       = $request->jenis_kelamin;
        $koordinator->tempat_lahir        = $request->tempat_lahir;
        $koordinator->tanggal_lahir       = $request->tanggal_lahir;
        $koordinator->alamat              = $request->alamat;
        $koordinator->no_hp               = $request->no_hp;
        $koordinator->email               = $request->email;
        $koordinator->pendidikan_terakhir = $request->pendidikan_terakhir;
        $koordinator->id_kecamatan        = $request->id_kecamatan;
        $koordinator->id_desa_kelurahan   = $request->id_desa_kelurahan;
        $koordinator->wilayah             = $request->wilayah;
        $koordinator->status              = $request->status;

        if ($request->hasFile('foto')) {
            if ($koordinator->foto && file_exists(public_path('storage/' . $koordinator->foto))) {
                unlink(public_path('storage/' . $koordinator->foto));
            }
            $file     = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_koordinator'), $namaFile);
            $koordinator->foto = 'foto_koordinator/' . $namaFile;
        }

        $koordinator->save();

        return back()->with('success', 'Data koordinator berhasil diupdate');
    }

    public function destroy($id)
    {
        $koordinator = Koordinator::findOrFail($id);

        if ($koordinator->foto) {
            $filePath = public_path('storage/' . $koordinator->foto);
            if (file_exists($filePath)) unlink($filePath);
        }

        $koordinator->delete();

        return back()->with('success', 'Data koordinator berhasil dihapus');
    }

    public function exportPdf(Request $request)
    {
        $query = Koordinator::with(['user', 'kecamatan', 'desa']);
        if ($request->has('status') && $request->status != '') {
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