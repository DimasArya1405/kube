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

        <button type="button" onclick="toggleModal('modal-tambah-user')"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition">
            Tambah User
        </button>
    </div>

{{-- Stats Section --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    {{-- Total User --}}
    <div class="bg-white p-4 rounded-lg shadow border">
        <p class="text-sm text-gray-500">Total User</p>
        <h3 class="text-2xl font-bold text-gray-800">
            {{ $total_user }}
        </h3>
    </div>

    {{-- User Aktif --}}
    <div class="bg-green-50 p-4 rounded-lg shadow border border-green-200">
        <p class="text-sm text-green-600">User Aktif</p>
        <h3 class="text-2xl font-bold text-green-700">
            {{ $user_aktif }} User
        </h3>
    </div>

    {{-- User Nonaktif --}}
    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600">User Nonaktif</p>
        <h3 class="text-2xl font-bold text-red-700">
            {{ $user_nonaktif }} User
        </h3>
    </div>
</div>

{{-- FILTER & SEARCH USER --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('admin.users') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">
            
            {{-- Input Pencarian Nama --}}
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Masukkan nama..."
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            
            {{-- Filter Role --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Role</label>
                <select name="role"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px] text-sm">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="ketua_kube" {{ request('role') == 'ketua_kube' ? 'selected' : '' }}>Ketua Kube</option>
                    <option value="pendamping" {{ request('role') == 'pendamping' ? 'selected' : '' }}>Pendamping</option>
                    <option value="koordinator" {{ request('role') == 'koordinator' ? 'selected' : '' }}>Koordinator</option>
                    <option value="kepala_dinas" {{ request('role') == 'kepala_dinas' ? 'selected' : '' }}>Kepala Dinas</option>
                </select>
            </div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px] text-sm">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                    Filter & Cari
                </button>

                <a href="{{ route('admin.users') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Info Badge Filter Aktif --}}
@if(request('search') || request('role') || request('status'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan data filter: 
        @if(request('search')) Kata kunci <span class="font-semibold text-gray-800">"{{ request('search') }}"</span> @endif
        @if(request('search') && (request('role') || request('status'))) dengan @endif
        @if(request('role')) Role <span class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', request('role'))) }}</span> @endif
        @if(request('role') && request('status')) dan @endif
        @if(request('status')) Status <span class="font-semibold text-gray-800">{{ ucfirst(request('status')) }}</span> @endif
    </div>
@endif

{{-- Table Section (Disertai Penjagaan Query String untuk Pagination) --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-center">No</th>
                    <th class="px-6 py-3 text-center">Nama</th>
                    <th class="px-6 py-3 text-center">Email</th>
                    <th class="px-6 py-3 text-center">No HP</th>
                    <th class="px-6 py-3 text-center">Alamat</th>
                    <th class="px-6 py-3 text-center">Role</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr class="border-b bg-white hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $users->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $user->nama }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $user->email }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $user->no_hp }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $user->alamat }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 text-center">
                            @if (is_null($user->role))
                                <span class="text-gray-400 font-bold" title="Belum Memiliki Role">-</span>
                            @else
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            @endif
                        </td>                        
                        <td class="px-6 py-4 font-medium text-gray-900 text-center">
                            @if ($user->status == 'aktif')
                                <span class="bg-emerald-200 px-2 py-1 text-xs rounded-md text-emerald-800">Aktif</span>
                            @else
                                <span class="bg-red-200 px-2 py-1 text-xs rounded-md text-red-800">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Detail Button --}}
                                <button onclick="detailUser('{{ $user->id_user }}')"
                                    class="group w-9 h-9 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-all duration-200" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:scale-110 group-hover:text-blue-600 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                        
                                {{-- Edit Button --}}
                                <button onclick="editUser('{{ $user->id_user }}')"
                                    class="group w-9 h-9 flex items-center justify-center rounded-lg text-amber-500 hover:bg-amber-50 transition-all duration-200" title="Edit">
                                    {{-- Fix xmlns typo from /lg to /svg --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:scale-110 group-hover:text-amber-600 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                        
                                {{-- Delete Button --}}
                                <form action="{{ route('admin.users.delete', $user->id_user) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')" class="inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="group w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-all duration-200" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:scale-110 group-hover:text-red-600 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
    {{ $users->appends(request()->query())->links() }}
</div>

{{-- Modal: Tambah User (UI Disamakan dengan Pencairan Bantuan) --}}
<div id="modal-tambah-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Backdrop Click to Close --}}
    <div class="fixed inset-0" onclick="toggleModal('modal-tambah-user')"></div>

    {{-- Container Modal: Menggunakan max-w-2xl agar form input tetap proporsional dan tidak terlalu melar --}}
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">

        {{-- Header Modal --}}
        <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-gray-800">Tambah User Baru</h3>
                <p class="text-gray-400 text-xs mt-0.5">Lengkapi data untuk membuat akun baru</p>
            </div>
            <button type="button" onclick="toggleModal('modal-tambah-user')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form & Body Modal --}}
        <form method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col overflow-hidden m-0">
            @csrf
            
            <div class="p-6 overflow-y-auto space-y-5 flex-1 max-h-[calc(90vh-140px)]">
                
                {{-- Grid Input Group --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Masukkan Nama" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all placeholder:text-gray-400" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">NIK (KTP)</label>
                        <input type="text" name="nik" placeholder="Masukkan NIK" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">No. Handphone</label>
                        <input type="text" name="no_hp" placeholder="Masukkan No. HP" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Email</label>
                        <input type="email" name="email" placeholder="Masukkan Email" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Kata Sandi</label>
                        <input type="password" name="password" placeholder="Masukkan Kata Sandi" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Kecamatan</label>
                        <select name="id_kecamatan" id="selectKecamatan" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer" required>
                            <option value="">Pilih Kecamatan</option>
                            @foreach ($kecamatan as $kec)
                                <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Desa/Kelurahan</label>
                        <select name="id_desa_kelurahan" id="selectDesa" disabled class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border text-gray-600 disabled:bg-gray-100 outline-none transition-all disabled:cursor-not-allowed" required>
                            <option value="">Pilih Desa/Kelurahan</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Domisili</label>
                        <textarea name="alamat" placeholder="Masukkan Alamat" rows="2" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border resize-none outline-none transition-all"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Hak Akses (Role)</label>
                        <select name="role" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer" required>
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
                        <div class="flex items-center h-[50px] gap-3 px-4 bg-green-50 border border-green-200 rounded-lg">
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

            {{-- Footer Modal --}}
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="toggleModal('modal-tambah-user')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Tutup
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                    Simpan Data User
                </button>
            </div>
        </form>

    </div>
</div>

{{-- Modal: Edit User (UI Disamakan dengan Pencairan Bantuan) --}}
<div id="modal-edit-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Backdrop Click to Close --}}
    <div class="fixed inset-0" onclick="toggleModal('modal-edit-user')"></div>

    {{-- Container Modal --}}
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">

        {{-- Header Modal --}}
        <div class="p-6 border-b flex justify-between items-center">
            <div>
                <h3 class="text-xl font-semibold text-gray-800">Perbarui Pengguna</h3>
                <p class="text-gray-400 text-xs mt-0.5">Ubah informasi akun dan hak akses pengguna</p>
            </div>
            <button type="button" onclick="toggleModal('modal-edit-user')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form & Body Modal --}}
        <form id="form-edit" method="POST" class="flex flex-col overflow-hidden m-0">
            @csrf
            @method('PUT')
            
            <div class="p-6 overflow-y-auto space-y-5 flex-1 max-h-[calc(90vh-140px)]">
                
                {{-- Grid Input Group --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="edit_nama" placeholder="Nama sesuai KTP" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all placeholder:text-gray-400" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">NIK (KTP)</label>
                        <input type="text" name="nik" id="edit_nik" placeholder="16 Digit NIK" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">No. Handphone</label>
                        <input type="text" name="no_hp" id="edit_no_hp" placeholder="08xxxx" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Email</label>
                        <input type="email" name="email" id="edit_email" placeholder="nama@email.com" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Kecamatan</label>
                        <select name="id_kecamatan" id="edit_kecamatan" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer" required>
                            @foreach ($kecamatan as $kec)
                                <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Desa/Kelurahan</label>
                        <select name="id_desa_kelurahan" id="edit_desa" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer" required>
                            @foreach ($desa as $d)
                                <option value="{{ $d->id_desa_kelurahan }}">{{ $d->nama_desa_kelurahan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Domisili</label>
                        <textarea name="alamat" id="edit_alamat" rows="2" placeholder="Nama jalan, blok, nomor rumah..." class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border resize-none outline-none transition-all"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Hak Akses (Role)</label>
                        <select name="role" id="edit_role" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer" required>
                            <option value="admin">Admin</option>
                            <option value="ketua_kube">Ketua KUBE</option>
                            <option value="pendamping">Pendamping</option>
                            <option value="koordinator">Koordinator</option>
                            <option value="kepala_dinas">Kepala Dinas</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Status Akun</label>
                        <select name="status" id="edit_status" class="w-full border-gray-200 rounded-lg p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer font-bold uppercase text-sm tracking-wide" required>
                            <option value="aktif" class="text-green-600">AKTIF</option>
                            <option value="nonaktif" class="text-red-600">NONAKTIF</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="toggleModal('modal-edit-user')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                    Tutup
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</div>

{{-- Modal: Detail User --}}
<div id="modal-detail-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Backdrop Click to Close --}}
    <div class="fixed inset-0" onclick="toggleModal('modal-detail-user')"></div>

    {{-- Container Modal --}}
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md overflow-hidden flex flex-col z-10 transform transition-all">
        
        {{-- Top Accent Bar (Ganti gradien mencolok ke aksen halus atau flat blue) --}}
        <div class="relative h-20 bg-blue-600 flex-shrink-0">
            <button type="button" onclick="toggleModal('modal-detail-user')" class="absolute top-4 right-4 text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Body Dokumen --}}
        <div class="px-6 pb-6 text-center flex-1">
            {{-- Avatar / Initial --}}
            <div class="relative -mt-10 mb-4 inline-block">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full border-4 border-white shadow-md text-blue-600 text-2xl font-bold uppercase" id="detail_initial">
                    U
                </div>
            </div>

            {{-- Nama & Role --}}
            <div class="mb-5">
                <h3 class="text-xl font-bold text-gray-800" id="detail_nama">Nama User</h3>
                <span class="inline-flex items-center mt-1.5 px-3 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 capitalize" id="detail_role">Role</span>
            </div>

            {{-- Informasi Detail --}}
            <div class="space-y-3 text-left">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="text-gray-400 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">NIK (KTP)</p>
                        <p class="text-sm font-semibold text-gray-700" id="detail_nik">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Email</p>
                        <p class="text-xs font-semibold text-gray-700 truncate" id="detail_email">-</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">No. Handphone</p>
                        <p class="text-xs font-semibold text-gray-700" id="detail_no_hp">-</p>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Wilayah Tugas</p>
                    <p class="text-sm font-semibold text-gray-700">
                        <span id="detail_desa">Desa</span>, <span id="detail_kecamatan">Kecamatan</span>
                    </p>
                </div>

                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Alamat Lengkap</p>
                    <p class="text-sm font-semibold text-gray-700 italic" id="detail_alamat">-</p>
                </div>
            </div>

            {{-- Footer Status & Tombol Tutup --}}
            <div class="mt-5 pt-4 border-t flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" id="detail_status_ping"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5" id="detail_status_dot"></span>
                    </span>
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider" id="detail_status">Aktif</span>
                </div>
                <button type="button" onclick="toggleModal('modal-detail-user')" class="px-4 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold rounded-lg transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    // Inisialisasi Element Dropdown
    const selectKecamatan = document.getElementById('selectKecamatan');
    const selectDesa = document.getElementById('selectDesa');

    // Fungsi Global Toggle Modal (Sama dengan sistem pencairan bantuan)
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    // Dynamic Dropdown Kecamatan -> Desa (Modal Tambah)
    selectKecamatan.addEventListener('change', function() {
        const idKecamatan = this.value;

        selectDesa.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
        
        if (idKecamatan) {
            selectDesa.disabled = false;
            selectDesa.classList.remove('bg-gray-100');
            selectDesa.classList.add('bg-white');
            selectDesa.innerHTML = '<option value="">Memuat...</option>';

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
                            if(desa.id_desa_kelurahan == data.id_desa_kelurahan) {
                                option.selected = true;
                            }
                            selectDesaEdit.appendChild(option);
                        });
                    });
            }
            toggleModal('modal-edit-user'); // Menggunakan sistem toggle baru
        });
    }

    // Dynamic Dropdown Kecamatan -> Desa (Modal Edit)
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
                toggleModal('modal-detail-user'); // Menggunakan sistem toggle baru
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengambil data user.');
            });
    }
</script>

@endsection