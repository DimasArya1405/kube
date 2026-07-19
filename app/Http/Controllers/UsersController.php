<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kecamatan;
use App\Models\DesaKelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; 
use App\Mail\AkunDiaktifkanMail;

class UsersController extends Controller
{
    /**
     * Menampilkan daftar user dengan pagination
     */
public function index(Request $request) 
{
    $query = User::query();

    // 1. Jalankan pencarian Nama
    if ($request->has('search') && $request->search != '') {
        $query->where('nama', 'LIKE', $request->search . '%');
    }

    // 2. Jalankan filter role
    if ($request->has('role') && $request->role != '') {
        $query->where('role', $request->role);
    }

    // 3. Jalankan filter status
    if ($request->has('status') && $request->status != '') {
        $query->where('status', $request->status);
    }

    // 🔥 TAMBAHAN: Urutkan status nonaktif dulu, baru aktif.
    // Jika statusnya sama, urutkan berdasarkan nama dari A-Z (opsional agar rapi)
    $query->orderBy('status', 'desc')
          ->orderBy('nama', 'asc');

    // 4. Eksekusi pagination setelah query diurutkan
    $users = $query->paginate(10);
    $kecamatan = Kecamatan::all();
    $desa = DesaKelurahan::all();
    $total_user = User::count();
    $user_aktif = User::where('status', 'aktif')->count();
    $user_nonaktif = User::where('status', 'nonaktif')->count();
    return view('admin.data_master.users', compact('users', 'kecamatan', 'desa', 'total_user', 'user_aktif', 'user_nonaktif'));
}

    /**
     * Menyimpan user baru ke database
     */
    public function store(Request $request)
    {
        // Tips: Sebaiknya tambahkan validasi di sini jika diperlukan
        User::create([
            'nama'              => $request->nama,
            'nik'               => $request->nik,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'no_hp'             => $request->no_hp,
            'alamat'            => $request->alamat,
            'id_kecamatan'      => $request->id_kecamatan,
            'id_desa_kelurahan' => $request->id_desa_kelurahan,
            'role'              => $request->role,
            'status'            => $request->status,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Mengambil data satu user untuk Edit/Detail (Response JSON)
     */
    public function edit($id)
    {
        // Eager load relasi kecamatan dan desa agar data wilayah muncul di modal detail
        $user = User::with(['kecamatan', 'desa'])->findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Memperbarui data user
     */
public function update(Request $request, $id)
{
    // 1. Ambil data user yang ingin diupdate
    $user = User::findOrFail($id);

    // 2. Jalankan validasi data (password diset 'nullable')
    $request->validate([
        'nama' => 'required|max:100',
        'nik' => 'required|max:30',
        'email' => 'required|email', 
        'no_hp' => 'required|max:15',
        'alamat' => 'required',
        'id_kecamatan' => 'required',
        'id_desa_kelurahan' => 'required',
        'role' => 'required',
        'status' => 'required',
        'password' => 'nullable',
    ]);

    // 3. Tampung semua data inputan kecuali password terlebih dahulu
    $data = [
        'nama' => $request->nama,
        'nik' => $request->nik,
        'email' => $request->email,
        'no_hp' => $request->no_hp,
        'alamat' => $request->alamat,
        'id_kecamatan' => $request->id_kecamatan,
        'id_desa_kelurahan' => $request->id_desa_kelurahan,
        'role' => $request->role,
        'status' => $request->status,
    ];

    // 4. LOGIKA NULLABLE: Cek apakah kolom password diisi oleh Admin?
    if ($request->filled('password')) {
        // Jika diisi, enkripsi password baru lalu masukkan ke array data
        $data['password'] = Hash::make($request->password);
    }

    // 5. Eksekusi update data ke database
    $user->update($data);
    return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
}

    /**
     * Menghapus data user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }

    public function aktifkan($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'aktif']);
        if ($user->email) {
            Mail::to($user->email)->send(new AkunDiaktifkanMail($user));
        }
        return redirect()->back()->with('success', 'Akun ' . $user->nama . ' telah berhasil diaktifkan!');
    }
}