<?php

namespace App\Http\Controllers;

use App\Models\Pendamping;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class PendampingController extends Controller
{
    public function index()
    {
        // Load pendamping dengan relasi user, kecamatan & desa diambil dari user
        $pendamping = Pendamping::with(['user.kecamatan', 'user.desa'])->get();

        // Ambil user dengan role pendamping dan status aktif
        $users = User::with(['kecamatan', 'desa'])
            ->where('role', 'pendamping')
            ->where('status', 'aktif')
            ->get();

        return view('admin.data_master.pendamping', compact('pendamping', 'users'));
    }

    // Mengembalikan data JSON untuk modal detail
    public function show($id)
    {
        $item = Pendamping::with(['user.kecamatan', 'user.desa'])->findOrFail($id);

        return response()->json([
            'id_pendamping'      => $item->id_pendamping,
            'nama_pendamping'    => $item->nama_pendamping,
            'nik'                => $item->nik,
            'jenis_kelamin'      => $item->jenis_kelamin,
            'tempat_lahir'       => $item->tempat_lahir,
            'tanggal_lahir'      => $item->tanggal_lahir,
            'no_hp'              => $item->no_hp,
            'email'              => $item->email,
            'pendidikan_terakhir'=> $item->pendidikan_terakhir,
            'kecamatan'          => $item->user?->kecamatan?->nama_kecamatan ?? '-',
            'desa'               => $item->user?->desa?->nama_desa_kelurahan ?? $item->user?->desa?->nama_desa ?? '-',
            'alamat'             => $item->alamat,
            'tanggal_mulai'      => $item->tanggal_mulai,
            'tanggal_selesai'    => $item->tanggal_selesai,
            'status'             => $item->status,
            'foto'               => $item->foto,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // 1. OTOMATIS BUAT AKUN LOGIN USER UNTUK PENDAMPING
        $user = User::create([
            'name'     => $request->nama_pendamping,
            'email'    => $request->email,
            'password' => bcrypt('password123'), // password default
            'role'     => 'pendamping',
        ]);

        // 2. MASUKKAN ID USER KE DATA PENDAMPING
        $data['id_user'] = $user->id;

        // 3. PROSES UPLOAD FOTO
        if ($request->hasFile('foto')) {
            $file     = $request->file('foto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/foto_pendamping'), $namaFile);
            $data['foto'] = $namaFile;
        }

        Pendamping::create($data);

        return redirect()->back()->with('success', 'Data pendamping berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $pendamping = Pendamping::findOrFail($id);

        $request->validate([
            'id_user'             => 'required|exists:users,id_user',
            'jenis_kelamin'       => 'required|in:L,P',
            'tempat_lahir'        => 'required|string|max:100',
            'tanggal_lahir'       => 'required|date',
            'pendidikan_terakhir' => 'required|string',
            'tanggal_mulai'       => 'required|date',
            'tanggal_selesai'     => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'              => 'required|in:Aktif,Tidak Aktif',
            'foto'                => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Auto-fill ulang dari user
        $user = User::findOrFail($request->id_user);

        $data = [
            'id_user'             => $user->id_user,
            'nik'                 => $user->nik,
            'nama_pendamping'     => $user->nama,
            'no_hp'               => $user->no_hp,
            'email'               => $user->email,
            'id_kecamatan'        => $user->id_kecamatan,
            'id_desa'             => $user->id_desa_kelurahan,
            'alamat'              => $user->alamat,
            'jenis_kelamin'       => $request->jenis_kelamin,
            'tempat_lahir'        => $request->tempat_lahir,
            'tanggal_lahir'       => $request->tanggal_lahir,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'tanggal_mulai'       => $request->tanggal_mulai,
            'tanggal_selesai'     => $request->tanggal_selesai,
            'status'              => $request->status,
        ];

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

        return redirect()->route('pendamping.index')->with('success', 'Data pendamping berhasil diupdate');
    }

    public function destroy($id)
    {
        $pendamping = Pendamping::findOrFail($id);

        if ($pendamping->foto && file_exists(public_path('storage/foto_pendamping/' . $pendamping->foto))) {
            unlink(public_path('storage/foto_pendamping/' . $pendamping->foto));
        }

        $pendamping->delete();

        return redirect()->back()->with('success', 'Data pendamping berhasil dihapus');
    }

    public function exportPdf()
    {
        $pendamping = Pendamping::with(['user.kecamatan', 'user.desa'])->get();
        $pdf = PDF::loadView('admin.data_master.pendamping_pdf', compact('pendamping'));
        return $pdf->download('data-pendamping.pdf');
    }

    public function exportExcel()
    {
        $pendamping = Pendamping::with(['user.kecamatan', 'user.desa'])->get();

        $filename = "data-pendamping.csv";
        $handle   = fopen($filename, 'w+');

        fputcsv($handle, ['DATA PENDAMPING KUBE']);
        fputcsv($handle, ['Dicetak: ' . now()->format('d-m-Y H:i')]);
        fputcsv($handle, []);

        fputcsv($handle, [
            'No', 'Nama Pendamping', 'NIK', 'Kecamatan', 'Desa/Kelurahan',
            'No HP', 'Email', 'Pendidikan Terakhir',
            'Tanggal Mulai', 'Tanggal Selesai', 'Status',
        ]);

        $no = 1;
        foreach ($pendamping as $item) {
            fputcsv($handle, [
                $no++,
                $item->nama_pendamping,
                $item->nik,
                $item->user?->kecamatan?->nama_kecamatan ?? '-',
                $item->user?->desa?->nama_desa ?? '-',
                $item->no_hp,
                $item->email,
                $item->pendidikan_terakhir,
                $item->tanggal_mulai,
                $item->tanggal_selesai ?? 'Masih Menjabat',
                $item->status,
            ]);
        }

        fputcsv($handle, []);
        fputcsv($handle, ['Total Data: ' . $pendamping->count()]);
        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
}