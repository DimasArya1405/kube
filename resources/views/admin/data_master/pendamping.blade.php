@extends('admin.layout')

@section('title', 'Data Pendamping - KUBE')

@section('breadcrumb')
    Data Master / <span class="text-gray-800">Data Pendamping</span>
@stop

@section('content')
{{-- HEADER --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Data Pendamping</h2>
        <p class="text-gray-500 mt-1">Kelola data, status, dan informasi detail pendamping.</p>
    </div>
    <div>
        <button type="button" onclick="openModal()"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition flex items-center shadow-sm">
            Tambah Pendamping
        </button>
    </div>
</div>

{{-- ALERT MESSAGES --}}
@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative mb-4 shadow-sm" role="alert">
    <span class="block sm:inline font-medium">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow-sm" role="alert">
    <strong class="font-bold">Oops!</strong>
    <span class="block sm:inline font-medium">{{ session('error') }}</span>
</div>
@endif

{{-- SUMMARY --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg shadow border border-blue-200">
        <p class="text-sm text-blue-600 font-medium">Pendamping Aktif</p>
        <h3 class="text-2xl font-bold text-blue-700">{{ $pendamping->where('status','Aktif')->count() }}</h3>
    </div>
    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600 font-medium">Pendamping Non-Aktif</p>
        <h3 class="text-2xl font-bold text-red-700">{{ $pendamping->where('status','Tidak Aktif')->count() }}</h3>
    </div>
</div>

{{-- FILTER & SEARCH AREA --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border p-4">
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4">
        <div class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" id="searchInput" class="w-full pl-10 pr-4 py-2.5 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-sm placeholder:text-gray-400" placeholder="Cari nama atau NIK pendamping...">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('pendamping.export.pdf') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm transition shadow-sm flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> 
                Export PDF
            </a>
            <a href="{{ route('pendamping.export.excel') }}" class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 text-sm transition shadow-sm flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
        </div>
    </div>
</div>

{{-- TABEL UTAMA --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-center">Foto</th>
                    <th class="px-6 py-3">Nama Pendamping</th>
                    <th class="px-6 py-3 text-center">NIK</th>
                    <th class="px-6 py-3 text-center">Kecamatan</th>
                    <th class="px-6 py-3 text-center">No HP</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendamping as $item)
                <tr class="searchable-row border-b bg-white hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 flex justify-center">
                        @if($item->foto)
                        <img src="{{ asset('storage/foto_pendamping/'.$item->foto) }}"
                            class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm">
                        @else
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $item->nama_pendamping }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $item->nik }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $item->user?->kecamatan?->nama_kecamatan ?? '-' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $item->no_hp }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                        @if($item->status == 'Aktif')
                            <span class="bg-emerald-100 border border-emerald-200 px-2 py-1 text-xs rounded-md text-emerald-700 font-semibold">Aktif</span>
                        @else
                            <span class="bg-red-100 border border-red-200 px-2 py-1 text-xs rounded-md text-red-700 font-semibold">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Button Detail --}}
                            <button type="button" onclick="openDetailModal('{{ $item->id_pendamping }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            
                            {{-- Button Edit --}}
                            <button type="button" onclick="openEditModal(
                                    '{{ $item->id_pendamping }}',
                                    '{{ $item->id_user }}',
                                    '{{ $item->nik }}',
                                    '{{ $item->nama_pendamping }}',
                                    '{{ $item->jenis_kelamin }}',
                                    '{{ $item->no_hp }}',
                                    '{{ $item->id_kecamatan }}',
                                    '{{ $item->kecamatan->nama_kecamatan ?? "" }}',
                                    '{{ $item->id_desa }}',
                                    '{{ $item->desa->nama_desa ?? "" }}',
                                    '{{ $item->status }}',
                                    '{{ $item->tanggal_mulai }}',
                                    '{{ $item->tanggal_selesai }}',
                                    '{{ $item->tempat_lahir }}',
                                    '{{ $item->tanggal_lahir }}',
                                    '{{ $item->pendidikan_terakhir }}',
                                    '{{ addslashes($item->alamat) }}',
                                    '{{ $item->email }}'
                                )" class="w-9 h-9 flex items-center justify-center rounded-lg text-yellow-500 hover:bg-yellow-50 transition-colors" title="Ubah Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            {{-- Button Delete --}}
                            <form action="{{ route('pendamping.delete', $item->id_pendamping) }}" method="POST" class="inline-block m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Hapus Data">
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
                    <td colspan="7" class="text-center py-10 text-gray-500 italic">
                        Belum ada data pendamping.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL DETAIL ================= --}}
<div id="DetailModal" onclick="closeDetailModal()" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-xl max-h-[90vh] overflow-hidden flex flex-col z-10" onclick="event.stopPropagation()">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Detail Pendamping</h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            {{-- Loading --}}
            <div id="detail-loading" class="flex justify-center items-center py-10">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </div>
            {{-- Konten --}}
            <div id="detail-content" class="hidden">
                {{-- Foto + Nama + Status --}}
                <div class="flex flex-col items-center mb-6">
                    <div id="detail-foto-wrapper" class="mb-3"></div>
                    <h3 id="detail-nama" class="text-xl font-bold text-gray-800"></h3>
                    <span id="detail-status-badge" class="mt-2 inline-flex items-center px-2 py-1 rounded text-xs font-medium text-white"></span>
                </div>
                {{-- Grid info --}}
                <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">NIK</p>
                        <p id="detail-nik" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Jenis Kelamin</p>
                        <p id="detail-jk" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tempat Lahir</p>
                        <p id="detail-tempat-lahir" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tanggal Lahir</p>
                        <p id="detail-tanggal-lahir" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">No HP</p>
                        <p id="detail-no-hp" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Email</p>
                        <p id="detail-email" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Pendidikan Terakhir</p>
                        <p id="detail-pendidikan" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Kecamatan</p>
                        <p id="detail-kecamatan" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Desa/Kelurahan</p>
                        <p id="detail-desa" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Alamat</p>
                        <p id="detail-alamat" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tanggal Mulai</p>
                        <p id="detail-tanggal-mulai" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tanggal Selesai</p>
                        <p id="detail-tanggal-selesai" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="modal" onclick="closeModal()" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10" onclick="event.stopPropagation()">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Pendamping</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('pendamping.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden flex-1">
            @csrf
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- 1. Pilih User Pendamping --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih User Pendamping <span class="text-red-500">*</span></label>
                        <select name="id_user" id="tambah_select_user" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" onchange="autoFillTambah(this)" required>
                            <option value="" disabled selected>-- Pilih Pendamping dari Akun User --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id_user }}"
                                data-nik="{{ $user->nik }}"
                                data-nama="{{ $user->nama }}"
                                data-nohp="{{ $user->no_hp }}"
                                data-email="{{ $user->email }}"
                                data-kecamatan-id="{{ $user->id_kecamatan }}"
                                data-kecamatan-nama="{{ $user->kecamatan->nama_kecamatan ?? '' }}"
                                data-desa-id="{{ $user->id_desa_kelurahan }}"
                                data-desa-nama="{{ $user->desa->nama_desa_kelurahan ?? '' }}"
                                data-alamat="{{ $user->alamat }}">
                                {{ $user->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. NIK (auto-fill, readonly) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" id="tambah_nik" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly placeholder="Otomatis terisi saat pilih user...">
                    </div>

                    {{-- 3. Nama (auto-fill, readonly) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pendamping</label>
                        <input type="text" id="tambah_nama" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly placeholder="Otomatis terisi saat pilih user...">
                    </div>

                    {{-- 4. Jenis Kelamin (manual) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <div class="flex gap-6 mt-1 text-sm">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="L" class="accent-blue-600" required> Laki-laki
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="jenis_kelamin" value="P" class="accent-pink-500"> Perempuan
                            </label>
                        </div>
                    </div>

                    {{-- 5. Tempat Lahir (manual) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder:text-gray-400" placeholder="Contoh: Cilacap" required>
                    </div>

                    {{-- 6. Tanggal Lahir (manual) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>

                    {{-- 7. No HP (auto-fill, readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                        <input type="text" id="tambah_nohp" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly placeholder="Otomatis terisi...">
                    </div>

                    {{-- 8. Email (auto-fill, readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="text" id="tambah_email" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly placeholder="Otomatis terisi...">
                    </div>

                    {{-- 9. Pendidikan Terakhir (manual) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                        <select name="pendidikan_terakhir" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                            <option value="" disabled selected>-- Pilih --</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    {{-- 10. Kecamatan (auto-fill, readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                        <input type="text" id="tambah_kecamatan_nama" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly placeholder="Otomatis terisi...">
                    </div>

                    {{-- 11. Desa (auto-fill, readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                        <input type="text" id="tambah_desa_nama" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly placeholder="Otomatis terisi...">
                    </div>

                    {{-- 12. Tanggal Mulai (manual) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>

                    {{-- 13. Tanggal Selesai (manual) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika masih menjabat</p>
                    </div>

                    {{-- 15. Alamat (auto-fill, readonly) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="tambah_alamat" rows="2" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm resize-none cursor-not-allowed" readonly placeholder="Otomatis terisi..."></textarea>
                    </div>

                 {{-- 16. Foto (manual) - Modal Tambah --}}
<div class="md:col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Foto <span class="text-xs text-gray-400 font-normal">(opsional, jpg/png maks. 2MB)</span>
    </label>
    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
        <input type="file" name="foto" id="tambahFoto" class="hidden" accept="image/jpg,image/jpeg,image/png" onchange="previewFoto(this, 'tambahFotoLabel', 'tambah-preview-wrap', 'tambah-preview-img')">
        <input type="text" id="tambahFotoLabel" readonly
            class="flex-1 px-4 py-2 text-sm text-gray-500 cursor-pointer focus:outline-none bg-transparent"
            placeholder="Belum ada file dipilih"
            onclick="document.getElementById('tambahFoto').click()">
        <button type="button"
            onclick="document.getElementById('tambahFoto').click()"
            class="bg-gray-400 hover:bg-gray-300 text-black text-sm px-4 py-2.5 transition font-medium border-l border-gray-300">
            Pilih File
        </button>
    </div>
    <div id="tambah-preview-wrap" class="mt-2 hidden">
        <img id="tambah-preview-img" src="#" alt="Preview"
            class="h-20 w-20 object-cover rounded-lg border border-gray-200 shadow-sm">
    </div>
</div>

                    {{-- Status otomatis Aktif --}}
                    <input type="hidden" name="status" value="Aktif">

                </div>
            </div>
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="EditModal" onclick="closeEditModal()" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10" onclick="event.stopPropagation()">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Perbarui Data Pendamping</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="formEditPendamping" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden flex-1">
            @csrf
            @method('PUT')
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- 1. Pilih User Pendamping --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih User Pendamping <span class="text-red-500">*</span></label>
                        <select name="id_user" id="edit_select_user" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" onchange="autoFillEdit(this)" required>
                            <option value="" disabled>-- Pilih Pendamping --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id_user }}"
                                data-nik="{{ $user->nik }}"
                                data-nama="{{ $user->nama }}"
                                data-nohp="{{ $user->no_hp }}"
                                data-email="{{ $user->email }}"
                                data-kecamatan-id="{{ $user->id_kecamatan }}"
                                data-kecamatan-nama="{{ $user->kecamatan->nama_kecamatan ?? '' }}"
                                data-desa-id="{{ $user->id_desa_kelurahan }}"
                                data-desa-nama="{{ $user->desa->nama_desa_kelurahan ?? '' }}"
                                data-alamat="{{ $user->alamat }}">
                                {{ $user->nama }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. NIK (auto-fill, readonly) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" id="edit_nik" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly>
                    </div>

                    {{-- 3. Nama (auto-fill, readonly) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pendamping</label>
                        <input type="text" id="edit_nama" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly>
                    </div>

                    {{-- 4. Jenis Kelamin --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select id="edit_jk" name="jenis_kelamin" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    {{-- 5. Tempat Lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_tempat_lahir" name="tempat_lahir" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    {{-- 6. Tanggal Lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" id="edit_tanggal_lahir" name="tanggal_lahir" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    {{-- 7. No HP (auto-fill, readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                        <input type="text" id="edit_nohp" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly>
                    </div>

                    {{-- 8. Email (auto-fill, readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="text" id="edit_email" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly>
                    </div>

                    {{-- 9. Pendidikan Terakhir --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                        <select id="edit_pendidikan" name="pendidikan_terakhir" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    {{-- 10. Kecamatan (auto-fill, readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                        <input type="text" id="edit_kecamatan_nama" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly>
                    </div>

                    {{-- 11. Desa (auto-fill, readonly) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                        <input type="text" id="edit_desa_nama" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly>
                    </div>

                    {{-- 12. Tanggal Mulai --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" id="edit_tanggal_mulai" name="tanggal_mulai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    {{-- 13. Tanggal Selesai --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" id="edit_tanggal_selesai" name="tanggal_selesai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika masih menjabat</p>
                    </div>

                    {{-- 14. Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Operasional</label>
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    {{-- 15. Alamat (auto-fill, readonly) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea id="edit_alamat" rows="2" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm resize-none cursor-not-allowed" readonly></textarea>
                    </div>

                    {{-- 16. Foto Baru - Modal Edit --}}
<div class="md:col-span-2">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        Foto Baru <span class="text-xs text-gray-400 font-normal">(opsional, biarkan kosong jika tidak diganti)</span>
    </label>
    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
        <input type="file" name="foto" id="editFoto" class="hidden" accept="image/jpg,image/jpeg,image/png" onchange="previewFoto(this, 'editFotoLabel', 'edit-preview-wrap', 'edit-preview-img')">
        <input type="text" id="editFotoLabel" readonly
            class="flex-1 px-4 py-2 text-sm text-gray-500 cursor-pointer focus:outline-none bg-transparent"
            placeholder="Belum ada file dipilih"
            onclick="document.getElementById('editFoto').click()">
        <button type="button"
            onclick="document.getElementById('editFoto').click()"
            class="bg-gray-400 hover:bg-gray-300 text-black text-sm px-4 py-2.5 transition font-medium border-l border-gray-300">
            Pilih File
        </button>
    </div>
    <div id="edit-preview-wrap" class="mt-2 hidden">
        <img id="edit-preview-img" src="#" alt="Preview"
            class="h-20 w-20 object-cover rounded-lg border border-gray-200 shadow-sm">
    </div>
</div>

                </div>
            </div>
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ===================== SCRIPT ===================== --}}
<script>
    // ================= MODAL TAMBAH =================
    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }

    // Auto-fill form tambah dari data user yang dipilih
    function autoFillTambah(select) {
        const opt = select.options[select.selectedIndex];
        document.getElementById('tambah_nik').value            = opt.getAttribute('data-nik') || '';
        document.getElementById('tambah_nama').value           = opt.getAttribute('data-nama') || '';
        document.getElementById('tambah_nohp').value           = opt.getAttribute('data-nohp') || '';
        document.getElementById('tambah_email').value          = opt.getAttribute('data-email') || '';
        document.getElementById('tambah_kecamatan_nama').value = opt.getAttribute('data-kecamatan-nama') || '';
        document.getElementById('tambah_desa_nama').value      = opt.getAttribute('data-desa-nama') || '';
        document.getElementById('tambah_alamat').value         = opt.getAttribute('data-alamat') || '';
    }

    // ================= MODAL DETAIL =================
    function openDetailModal(id) {
        document.getElementById('DetailModal').classList.remove('hidden');
        document.getElementById('detail-loading').classList.remove('hidden');
        document.getElementById('detail-content').classList.add('hidden');

        fetch('/admin/pendamping/' + id)
            .then(response => response.json())
            .then(data => {
                // Foto
                const fotoWrapper = document.getElementById('detail-foto-wrapper');
                if (data.foto) {
                    fotoWrapper.innerHTML = `<img src="/storage/foto_pendamping/${data.foto}"
                        class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">`;
                } else {
                    fotoWrapper.innerHTML = `
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>`;
                }

                // Nama & status badge
                document.getElementById('detail-nama').textContent = data.nama_pendamping ?? '-';
                const badge = document.getElementById('detail-status-badge');
                if (data.status === 'Aktif') {
                    badge.textContent = 'Aktif';
                    badge.className = 'mt-2 inline-flex items-center px-2 py-1 rounded text-xs font-semibold text-emerald-700 bg-emerald-100 border border-emerald-200';
                } else {
                    badge.textContent = data.status ?? '-';
                    badge.className = 'mt-2 inline-flex items-center px-2 py-1 rounded text-xs font-semibold text-red-700 bg-red-100 border border-red-200';
                }

                // Field detail
                document.getElementById('detail-nik').textContent           = data.nik ?? '-';
                document.getElementById('detail-jk').textContent            = data.jenis_kelamin === 'L' ? 'Laki-laki' : (data.jenis_kelamin === 'P' ? 'Perempuan' : '-');
                document.getElementById('detail-tempat-lahir').textContent  = data.tempat_lahir ?? '-';
                document.getElementById('detail-tanggal-lahir').textContent = data.tanggal_lahir
                    ? new Date(data.tanggal_lahir).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })
                    : '-';
                document.getElementById('detail-no-hp').textContent         = data.no_hp ?? '-';
                document.getElementById('detail-email').textContent         = data.email ?? '-';
                document.getElementById('detail-pendidikan').textContent    = data.pendidikan_terakhir ?? '-';
                document.getElementById('detail-kecamatan').textContent     = data.kecamatan ?? '-';
                document.getElementById('detail-desa').textContent          = data.desa ?? '-';
                document.getElementById('detail-alamat').textContent        = data.alamat ?? '-';
                document.getElementById('detail-tanggal-mulai').textContent = data.tanggal_mulai
                    ? new Date(data.tanggal_mulai).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })
                    : '-';
                document.getElementById('detail-tanggal-selesai').textContent = data.tanggal_selesai
                    ? new Date(data.tanggal_selesai).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })
                    : 'Masih Menjabat';

                document.getElementById('detail-loading').classList.add('hidden');
                document.getElementById('detail-content').classList.remove('hidden');
            })
            .catch(() => {
                document.getElementById('detail-loading').innerHTML =
                    '<p class="text-red-500 text-sm text-center py-6">Gagal memuat data. Silakan coba lagi.</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('DetailModal').classList.add('hidden');
    }

    // ================= MODAL EDIT =================
    function openEditModal(id, id_user, nik, nama, jk, nohp, kecamatanId, kecamatanNama,
        desaId, desaNama, status, tanggal_mulai, tanggal_selesai,
        tempat_lahir, tanggal_lahir, pendidikan, alamat, email) {

        // Set action form
        document.getElementById('formEditPendamping').action = "/admin/pendamping/" + id;

        // Set dropdown user
        document.getElementById('edit_select_user').value = id_user;

        // Auto-fill readonly fields
        document.getElementById('edit_nik').value            = nik;
        document.getElementById('edit_nama').value           = nama;
        document.getElementById('edit_nohp').value           = nohp;
        document.getElementById('edit_email').value          = email;
        document.getElementById('edit_kecamatan_nama').value = kecamatanNama;
        document.getElementById('edit_desa_nama').value      = desaNama;
        document.getElementById('edit_alamat').value         = alamat;

        // Set field manual
        document.getElementById('edit_jk').value              = jk;
        document.getElementById('edit_tempat_lahir').value    = tempat_lahir;
        document.getElementById('edit_tanggal_lahir').value   = tanggal_lahir;
        document.getElementById('edit_pendidikan').value      = pendidikan;
        document.getElementById('edit_tanggal_mulai').value   = tanggal_mulai;
        document.getElementById('edit_tanggal_selesai').value = tanggal_selesai;
        document.getElementById('edit_status').value          = status;

        document.getElementById('EditModal').classList.remove('hidden');
    }

    // Auto-fill form edit jika user diganti
    function autoFillEdit(select) {
        const opt = select.options[select.selectedIndex];
        document.getElementById('edit_nik').value            = opt.getAttribute('data-nik') || '';
        document.getElementById('edit_nama').value           = opt.getAttribute('data-nama') || '';
        document.getElementById('edit_nohp').value           = opt.getAttribute('data-nohp') || '';
        document.getElementById('edit_email').value          = opt.getAttribute('data-email') || '';
        document.getElementById('edit_kecamatan_nama').value = opt.getAttribute('data-kecamatan-nama') || '';
        document.getElementById('edit_desa_nama').value      = opt.getAttribute('data-desa-nama') || '';
        document.getElementById('edit_alamat').value         = opt.getAttribute('data-alamat') || '';
    }

    function closeEditModal() {
        document.getElementById('EditModal').classList.add('hidden');
    }

    // ================= SEARCH =================
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.searchable-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
</script>
@endsection