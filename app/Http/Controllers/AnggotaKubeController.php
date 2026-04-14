<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKube;
use App\Models\Kube;
use Illuminate\Http\Request;

class AnggotaKubeController extends Controller
{
    public function index()
    {
        $anggotas = AnggotaKube::with('kube')->get();

        $kubes = Kube::orderBy('nama_kube', 'asc')->get();

        return view('admin.data_master.anggota_kube', compact('anggotas', 'kubes'));
    }

    public function store(Request $request)
    {
        // 1. Validasi inputan form
        $request->validate([
            'id_kube' => 'required|integer',
            'nik' => 'required|string|max:16',
            'nama_anggota' => 'required|string|max:100',
            'jabatan' => 'required|string|max:20',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        // 🔥 2. LOGIKA SATPAM: CEK KETUA MAKSIMAL 1 🔥
        if ($request->jabatan == 'Ketua') {
            // Cek apakah di KUBE ini sudah ada yang jabatannya Ketua
            $cekKetua = AnggotaKube::where('id_kube', $request->id_kube)
                ->where('jabatan', 'Ketua')
                ->exists();

            // Kalau udah ada, lempar kembali ke halaman sebelumnya bawa pesan error
            if ($cekKetua) {
                return redirect()->back()->with('error', 'Gagal! KUBE ini sudah memiliki Ketua. Silakan pilih jabatan lain.');
            }
        }

        // 3. Kalau lolos pengecekan (bukan ketua, atau belum ada ketua), baru simpan ke database
        AnggotaKube::create([
            'id_kube' => $request->id_kube,
            'nik' => $request->nik,
            'nama_anggota' => $request->nama_anggota,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->back()->with('success', 'Data Anggota berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $anggota = AnggotaKube::where('id_anggota', $id)->firstOrFail();
        $anggota->delete();

        return redirect()->back()->with('success', 'Data Anggota berhasil dihapus!');
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'id_kube' => 'required|integer',
            'nik' => 'required|string|max:16',
            'nama_anggota' => 'required|string|max:100',
            'jabatan' => 'required|string|max:20',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
        ]);

        // 2. Ambil data anggota yang mau diupdate
        $anggota = AnggotaKube::where('id_anggota', $id)->firstOrFail();

        // 🔥 3. CEK KETUA (biar tetap cuma 1)
        if ($request->jabatan == 'Ketua') {
            $cekKetua = AnggotaKube::where('id_kube', $request->id_kube)
                ->where('jabatan', 'Ketua')
                ->where('id_anggota', '!=', $id) // ❗ ini penting (exclude dirinya sendiri)
                ->exists();

            if ($cekKetua) {
                return redirect()->back()->with('error', 'Gagal! KUBE ini sudah memiliki Ketua.');
            }
        }

        // 4. Update data
        $anggota->update([
            'id_kube' => $request->id_kube,
            'nik' => $request->nik,
            'nama_anggota' => $request->nama_anggota,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        // 5. Redirect
        return redirect()->back()->with('success', 'Data Anggota berhasil diupdate!');
    }
}
