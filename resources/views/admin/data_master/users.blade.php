@extends('admin.layout')

@section('title', 'Data User - KUBE')

@section('breadcrumb')
    Dashboard / <span class="text-gray-800">Data User</span>
@stop

@section('content')

    {{-- Header Section --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Data User</h2>
            <p class="text-gray-500 mt-1">Kelola seluruh akun pengguna KUBE.</p>
        </div>

        <button data-modal-target="modal-tambah-user" data-modal-toggle="modal-tambah-user"
            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
            Tambah User Baru
        </button>
    </div>

    {{-- Stats Section --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {{-- Total User --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 transition-all hover:shadow-md">
            <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total User</p>
                <h4 class="text-2xl font-black text-gray-800">{{ $total_user }}</h4>
            </div>
        </div>

        {{-- User Aktif --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 transition-all hover:shadow-md">
            <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">User Aktif</p>
                <h4 class="text-2xl font-black text-gray-800">{{ $user_aktif }}</h4>
            </div>
        </div>

        {{-- User Nonaktif --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 transition-all hover:shadow-md">
            <div class="w-14 h-14 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">User Nonaktif</p>
                <h4 class="text-2xl font-black text-gray-800">{{ $user_nonaktif }}</h4>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">No HP</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">{{ $users->firstItem() + $index }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $user->nama }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->no_hp }}</td>
                            <td class="px-6 py-4">{{ $user->alamat }}</td>
                            <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    {{ $user->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">

                                    {{-- Detail Button --}}
                                    <button onclick="detailUser('{{ $user->id_user }}')"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors"
                                        title="Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    {{-- Edit Button --}}
                                    <button onclick="editUser('{{ $user->id_user }}')"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg text-yellow-500 hover:bg-yellow-50 transition-colors"
                                        title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.users.delete', $user->id_user) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline-block m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500 italic">Belum ada data user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

{{-- Modal: Tambah User --}}
<div id="modal-tambah-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-md p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100 transform transition-transform duration-300 scale-100">
        
        <div class="flex justify-between items-center px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Tambah User Baru</h3>
                    <p class="text-blue-100 text-xs">Lengkapi data untuk membuat akun baru</p>
                </div>
            </div>
            <button type="button" data-modal-toggle="modal-tambah-user" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col overflow-hidden bg-gray-50/50">
            @csrf
            <div class="p-8 overflow-y-auto space-y-6 flex-grow custom-scrollbar">
                
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama Lengkap</label>
                            <input type="text" name="nama" placeholder="Masukkan Nama" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all placeholder:text-gray-400" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">NIK (KTP)</label>
                            <input type="text" name="nik" placeholder="Masukkan NIK" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">No. Handphone</label>
                            <input type="text" name="no_hp" placeholder="Masukkan No. HP" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Email</label>
                            <input type="email" name="email" placeholder="Masukkan Email" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Kata Sandi</label>
                            <input type="password" name="password" placeholder="Masukkan Kata Sandi" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Kecamatan</label>
                            <select name="id_kecamatan" id="selectKecamatan" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none appearance-none transition-all cursor-pointer" required>
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Desa/Kelurahan</label>
                            <select name="id_desa_kelurahan" id="selectDesa" disabled class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border text-gray-600 disabled:bg-gray-100 outline-none appearance-none transition-all disabled:cursor-not-allowed" required>
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Domisili</label>
                            <textarea name="alamat" placeholder="Masukkan Alamat" rows="2" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border resize-none outline-none transition-all"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Hak Akses (Role)</label>
                            <select name="role" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer">
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="ketua_kube">Ketua KUBE</option>
                                <option value="pendamping">Pendamping</option>
                                <option value="koordinator">Koordinator</option>
                                <option value="kepala_dinas">Kepala Dinas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Status Aktivasi</label>
                            <div class="flex items-center h-[50px] gap-3 px-4 bg-green-50 border border-green-200 rounded-xl">
                                <span class="relative flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                </span>
                                <span class="text-sm font-bold text-green-700 uppercase tracking-widest">Aktif</span>
                            </div>
                            <input type="hidden" name="status" value="aktif">
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 bg-white border-t flex gap-4 flex-shrink-0 px-8">
                <button type="button" data-modal-toggle="modal-tambah-user" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-[2] px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-200 font-bold transition-all transform active:scale-[0.98]">
                    Simpan Data User
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Edit User --}}
<div id="modal-edit-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-md p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100 transform transition-transform duration-300 scale-100">
        
        <div class="flex justify-between items-center px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 text-white.828 2.828 0 114 4L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Perbarui Pengguna</h3>
                    <p class="text-amber-50 text-xs">Ubah informasi akun dan hak akses pengguna</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="form-edit" method="POST" class="flex flex-col overflow-hidden bg-gray-50/50">
            @csrf
            @method('PUT')
            
            <div class="p-8 overflow-y-auto space-y-6 flex-grow custom-scrollbar">
                
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama Lengkap</label>
                            <input type="text" name="nama" id="edit_nama" placeholder="Nama sesuai KTP" 
                                class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">NIK</label>
                            <input type="text" name="nik" id="edit_nik" placeholder="16 Digit NIK" 
                                class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">No. Handphone</label>
                            <input type="text" name="no_hp" id="edit_no_hp" placeholder="08xxxx" 
                                class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Email Aktif</label>
                            <input type="email" name="email" id="edit_email" placeholder="nama@email.com" 
                                class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Kecamatan</label>
                            <select name="id_kecamatan" id="edit_kecamatan" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border bg-white outline-none transition-all cursor-pointer appearance-none">
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Desa/Kelurahan</label>
                            <select name="id_desa_kelurahan" id="edit_desa" disabled class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border text-gray-600 disabled:bg-gray-100 outline-none appearance-none transition-all disabled:cursor-not-allowed" required>
                                @foreach ($desa as $d)
                                    <option value="{{ $d->id_desa_kelurahan }}">{{ $d->nama_desa_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" id="edit_alamat" rows="2" placeholder="Nama jalan, blok, nomor rumah..." 
                                class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border resize-none outline-none transition-all"></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Hak Akses (Role)</label>
                            <select name="role" id="edit_role" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border bg-white outline-none transition-all cursor-pointer">
                                <option value="admin">Admin</option>
                                <option value="ketua_kube">Ketua KUBE</option>
                                <option value="pendamping">Pendamping</option>
                                <option value="koordinator">Koordinator</option>
                                <option value="kepala_dinas">Kepala Dinas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Status Akun</label>
                            <select name="status" id="edit_status" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border bg-white outline-none transition-all cursor-pointer font-bold uppercase text-sm tracking-wide">
                                <option value="aktif" class="text-green-600">AKTIF</option>
                                <option value="nonaktif" class="text-red-600">NONAKTIF</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border-t flex gap-4 flex-shrink-0 px-8">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-[2] px-4 py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl hover:from-amber-600 hover:to-amber-700 shadow-lg shadow-amber-100 font-bold transition-all transform active:scale-[0.98]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Detail User --}}
<div id="modal-detail-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
            <div class="relative h-24 bg-gradient-to-r from-blue-600 to-indigo-700">
                <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="px-6 pb-6 text-center">
                <div class="relative -mt-12 mb-4 inline-block">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full border-4 border-white shadow-lg text-blue-600 text-3xl font-bold uppercase" id="detail_initial">
                        U
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800" id="detail_nama">Nama User</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize" id="detail_role">Role</span>
                </div>

                <div class="space-y-4 text-left">
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">NIK</p>
                            <p class="text-sm font-semibold text-gray-700" id="detail_nik">-</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Email</p>
                            <p class="text-sm font-semibold text-gray-700 truncate" id="detail_email">-</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl">
                            <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">No. HP</p>
                            <p class="text-sm font-semibold text-gray-700" id="detail_no_hp">-</p>
                        </div>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Wilayah</p>
                        <p class="text-sm font-semibold text-gray-700">
                            <span id="detail_desa">Desa</span>, <span id="detail_kecamatan">Kecamatan</span>
                        </p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Alamat Lengkap</p>
                        <p class="text-sm font-semibold text-gray-700 italic" id="detail_alamat">-</p>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" id="detail_status_ping"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3" id="detail_status_dot"></span>
                        </span>
                        <span class="text-sm font-bold text-gray-600 uppercase tracking-widest" id="detail_status">Aktif</span>
                    </div>
                    <button onclick="closeDetailModal()" class="text-sm font-bold text-blue-600 hover:text-blue-800">Tutup</button>
                </div>
            </div>
        </div>
</div>

<script>
        lucide.createIcons();

        const modalEdit = document.getElementById('modal-edit-user');
        const modalDetail = document.getElementById('modal-detail-user');
        const selectKecamatan = document.getElementById('selectKecamatan');
        const selectDesa = document.getElementById('selectDesa');

        selectKecamatan.addEventListener('change', function() {
            const idKecamatan = this.value;

            // Reset dropdown desa
            selectDesa.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
            
            if (idKecamatan) {
                // Aktifkan dropdown desa
                selectDesa.disabled = false;
                selectDesa.classList.remove('bg-gray-100');
                selectDesa.classList.add('bg-white');
                selectDesa.innerHTML = '<option value="">Memuat...</option>';

                // Ambil data menggunakan AJAX/Fetch
                fetch(`/get-desa/${idKecamatan}`)
                    .then(response => response.json())
                    .then(data => {
                        selectDesa.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                        data.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.id_desa_kelurahan;
                            option.textContent = desa.nama_desa_kelurahan;
                            selectDesa.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal mengambil data desa');
                    });
            } else {
                // Jika kecamatan dikosongkan lagi
                selectDesa.disabled = true;
                selectDesa.classList.add('bg-gray-100');
            }
        });

        // -- EDIT USER --
        function editUser(id) {
            fetch('/admin/users/edit/' + id)
            .then(res => res.json())
            .then(data => {
                document.getElementById('edit_nama').value = data.nama;
                document.getElementById('edit_nik').value = data.nik;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_no_hp').value = data.no_hp;
                document.getElementById('edit_alamat').value = data.alamat;
                document.getElementById('edit_role').value = data.role;
                document.getElementById('edit_status').value = data.status;
                document.getElementById('edit_kecamatan').value = data.id_kecamatan;
                document.getElementById('form-edit').action = '/admin/users/update/' + id;

            // PENTING: Ambil data desa berdasarkan kecamatan user tersebut
            const selectDesaEdit = document.getElementById('edit_desa');
            
            if (data.id_kecamatan) {
                selectDesaEdit.disabled = false;
                selectDesaEdit.classList.remove('bg-gray-100');
                
                fetch(`/get-desa/${data.id_kecamatan}`)
                    .then(res => res.json())
                    .then(desaData => {
                        selectDesaEdit.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                        desaData.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.id_desa_kelurahan;
                            option.textContent = desa.nama_desa_kelurahan;
                            // Cek jika ini adalah desa milik si user
                            if(desa.id_desa_kelurahan == data.id_desa_kelurahan) {
                                option.selected = true;
                            }
                            selectDesaEdit.appendChild(option);
                        });
                    });
                }
            modalEdit.classList.remove('hidden');
        });
    }

        function closeEditModal() {
                modalEdit.classList.add('hidden');
            }
        // Listener untuk perubahan kecamatan di Modal EDIT
        document.getElementById('edit_kecamatan').addEventListener('change', function() {
        const idKecamatan = this.value;
        const selectDesaEdit = document.getElementById('edit_desa');
        selectDesaEdit.innerHTML = '<option value="">Memuat...</option>';
    
    if (idKecamatan) {
        fetch(`/get-desa/${idKecamatan}`)
            .then(response => response.json())
            .then(data => {
                selectDesaEdit.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                data.forEach(desa => {
                    const option = document.createElement('option');
                    option.value = desa.id_desa_kelurahan;
                    option.textContent = desa.nama_desa_kelurahan;
                    selectDesaEdit.appendChild(option);
                });
                selectDesaEdit.disabled = false;
                selectDesaEdit.classList.remove('bg-gray-100');
            });
    } else {
        selectDesaEdit.disabled = true;
        selectDesaEdit.classList.add('bg-gray-100');
        }
    });

        // -- DETAIL USER --
        function detailUser(id) {
            fetch('/admin/users/edit/' + id)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('detail_nama').innerText = data.nama || '-';
                    document.getElementById('detail_email').innerText = data.email || '-';
                    document.getElementById('detail_nik').innerText = data.nik || '-';
                    document.getElementById('detail_no_hp').innerText = data.no_hp || '-';
                    document.getElementById('detail_alamat').innerText = data.alamat || '-';

                    const namaKecamatan = data.kecamatan?.nama_kecamatan || '-';
                    const namaDesa = data.desa?.nama_desa_kelurahan || '-';
                    document.getElementById('detail_kecamatan').innerText = "Kecamatan " + namaKecamatan;
                    document.getElementById('detail_desa').innerText = "Desa/Kelurahan " + namaDesa;

                    const roleElement = document.getElementById('detail_role');
                    roleElement.innerText = data.role.replace('_', ' ');

                    const initial = data.nama ? data.nama.charAt(0).toUpperCase() : 'U';
                    document.getElementById('detail_initial').innerText = initial;

                    const statusPing = document.getElementById('detail_status_ping');
                    const statusDot = document.getElementById('detail_status_dot');
                    const statusText = document.getElementById('detail_status');
                    statusText.innerText = data.status;

                    if (data.status.toLowerCase() === 'aktif') {
                        statusText.className = "text-sm font-bold text-green-600 uppercase tracking-widest";
                        statusPing.className = "animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75";
                        statusDot.className = "relative inline-flex rounded-full h-3 w-3 bg-green-500";
                    } else {
                        statusText.className = "text-sm font-bold text-red-600 uppercase tracking-widest";
                        statusPing.className = "";
                        statusDot.className = "relative inline-flex rounded-full h-3 w-3 bg-red-500";
                    }
                    modalDetail.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal mengambil data user.');
                });
            }

        function closeDetailModal() {
            modalDetail.classList.add('hidden');
        }

        window.onclick = function(event) {
            if (event.target == modalEdit) closeEditModal();
            if (event.target == modalDetail) closeDetailModal();
        }
</script>

@endsection