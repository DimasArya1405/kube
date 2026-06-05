{{-- KATRINA --}}
@extends('admin.layout')

@section('title', 'Data Koordinator - KUBE')

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Koordinator</h2>
        <p class="text-gray-500 mt-1">Kelola seluruh data koordinator KUBE.</p>
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div class="flex gap-4 mb-6">
    <div class="bg-green-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Koordinator Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $koordinator->where('status','aktif')->count() }}</p>
    </div>
    <div class="bg-orange-300 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Koordinator Non-Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $koordinator->where('status','non-aktif')->count() }}</p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">

    {{-- Search --}}
    <div class="relative flex-1 min-w-[200px]">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
            </svg>
        </span>
        <input type="text" id="searchInput" placeholder="Cari nama, NIK..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    {{-- Filter Status --}}
    <select id="filterStatus" onchange="filterByStatus(this.value)"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
    </select>

    {{-- Ekspor PDF --}}
    <a href="{{ route('koordinator.export.pdf') }}{{ request('status') ? '?status='.request('status') : '' }}"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        Ekspor PDF
    </a>

    {{-- Ekspor Excel --}}
    <a href="{{ route('koordinator.export.excel') }}{{ request('status') ? '?status='.request('status') : '' }}"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        Ekspor Excel
    </a>

    {{-- Tambah --}}
    <button onclick="openTambahModal()"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + Tambah Koor
    </button>

</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">NIK</th>
                    <th class="px-4 py-3">No HP</th>
                    <th class="px-4 py-3">Jenis Kelamin</th>
                    <th class="px-4 py-3">Tanggal Lahir</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($koordinator as $item)
                <tr class="border-t border-gray-100 hover:bg-gray-50 searchable-row">

                    {{-- Foto --}}
                    <td class="px-4 py-3">
                        @if($item->foto)
                            <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto"
                                class="w-9 h-9 rounded-full object-cover border border-gray-200">
                        @else
                            <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->user->nama ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->user->nik ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->user->no_hp ?? '-' }}</td>

                    {{-- Jenis Kelamin --}}
                    <td class="px-4 py-3">
                        @if($item->jenis_kelamin == 'L')
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">Laki-laki</span>
                        @elseif($item->jenis_kelamin == 'P')
                            <span class="bg-pink-100 text-pink-600 text-xs font-semibold px-2.5 py-1 rounded-full">Perempuan</span>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>

                    {{-- Tanggal Lahir --}}
                    <td class="px-4 py-3 text-gray-700">
                        {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') : '-' }}
                    </td>

                    <td class="px-4 py-3">
                        @if($item->status == 'aktif')
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>
                        @else
                        <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">Non-Aktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">

                            {{-- Detail --}}
                            <button
                                data-id="{{ $item->id_koor }}"
                                data-nama="{{ $item->user->nama ?? '-' }}"
                                data-nik="{{ $item->user->nik ?? '-' }}"
                                data-nohp="{{ $item->user->no_hp ?? '-' }}"
                                data-alamat="{{ $item->user->alamat ?? '-' }}"
                                data-jk="{{ $item->jenis_kelamin ?? '' }}"
                                data-tgl="{{ $item->tanggal_lahir ?? '' }}"
                                data-foto="{{ $item->foto ? asset('storage/' . $item->foto) : '' }}"
                                data-status="{{ $item->status }}"
                                onclick="openDetailModal(this)"
                                class="text-blue-500 hover:text-blue-700" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Edit --}}
                            <button
                                data-id="{{ $item->id_koor }}"
                                data-nama="{{ $item->user->nama ?? '-' }}"
                                data-nik="{{ $item->user->nik ?? '-' }}"
                                data-nohp="{{ $item->user->no_hp ?? '-' }}"
                                data-alamat="{{ $item->user->alamat ?? '-' }}"
                                data-status="{{ $item->status }}"
                                data-jk="{{ $item->jenis_kelamin ?? '' }}"
                                data-tgl="{{ $item->tanggal_lahir ?? '' }}"
                                data-foto="{{ $item->foto ? asset('storage/' . $item->foto) : '' }}"

                                onclick="openEditModal(this)"
                                class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            {{-- Hapus --}}
                            <form action="{{ route('koordinator.delete', $item->id_koor) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-400">Belum ada data koordinator.</td>

                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- ==================== MODAL TAMBAH (Step 1: Pilih User) ==================== --}}
<div id="modal-tambah-koor" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">
    <div class="relative p-4 w-full max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Koordinator</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Pilih user koordinator yang belum diproses, review datanya, lalu tambah koordinator.</p>

                </div>
                <button onclick="closeTambahModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>

            <div class="p-5 max-h-[60vh] overflow-y-auto">
                @if($users->isEmpty())
                    <p class="text-center text-gray-400 py-6">Tidak ada user koordinator yang belum diproses.</p>
                @else
                    <p class="text-sm text-gray-500 mb-3">Klik <strong>Lihat Detail</strong></p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2">Nama</th>
                                    <th class="px-3 py-2">NIK</th>
                                    <th class="px-3 py-2">No HP</th>
                                    <th class="px-3 py-2">Alamat</th>
                                    <th class="px-3 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-t border-gray-100 hover:bg-gray-50">
                                    <td class="px-3 py-2 font-medium text-gray-800">{{ $user->nama }}</td>
                                    <td class="px-3 py-2">{{ $user->nik ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $user->no_hp ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $user->alamat ?? '-' }}</td>
                                    <td class="px-3 py-2">
                                        <button
                                            onclick="toggleDetail(this)"

                                            data-id="{{ $user->id_user }}"
                                            data-nama="{{ $user->nama }}"
                                            data-nik="{{ $user->nik ?? '-' }}"
                                            data-nohp="{{ $user->no_hp ?? '-' }}"
                                            data-email="{{ $user->email ?? '-' }}"
                                            data-alamat="{{ $user->alamat ?? '-' }}"
                                            class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg whitespace-nowrap">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>

                                {{-- Row detail expand --}}
                                <tr class="detail-row hidden bg-blue-50 border-t border-blue-100" id="detail-{{ $user->id_user }}">
                                    <td colspan="5" class="px-4 py-3">
                                        <div class="flex flex-wrap gap-6 text-sm">
                                            <div>
                                                <p class="text-xs text-gray-400 mb-0.5">Email</p>
                                                <p class="text-gray-700 detail-email"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-400 mb-0.5">Alamat</p>
                                                <p class="text-gray-700 detail-alamat"></p>
                                            </div>
                                            <div class="ml-auto flex items-end">
                                                <button
                                                    data-id="{{ $user->id_user }}"
                                                    data-nama="{{ $user->nama }}"
                                                    data-nik="{{ $user->nik ?? '-' }}"
                                                    data-nohp="{{ $user->no_hp ?? '-' }}"
                                                    data-email="{{ $user->email ?? '-' }}"
                                                    data-alamat="{{ $user->alamat ?? '-' }}"
                                                    onclick="openPreviewModal(this)"
                                                    class="bg-green-500 hover:bg-green-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg">
                                                    Tambah Koordinator
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="flex justify-end p-4 border-t">
                <button onclick="closeTambahModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL PREVIEW USER (Step 2: Review + Isi Data Tambahan) ==================== --}}
<div id="modal-preview-user" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] flex justify-center items-center w-full h-full bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Detail User Koordinator</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Review data user, lalu lengkapi data koordinatornya.</p>
                </div>
                <button onclick="closePreviewModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>

            <div class="px-5 pt-5 pb-3 max-h-[75vh] overflow-y-auto">
                <form id="form-tambah-koor" action="{{ route('koordinator.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_user" id="preview-id-user">

                    {{-- Data dari users (read-only display) --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-3 tracking-wide">Data User</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">Nama</p>
                                <p class="text-sm font-semibold text-gray-800" id="preview-nama"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">NIK</p>
                                <p class="text-sm text-gray-700" id="preview-nik"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">No HP</p>
                                <p class="text-sm text-gray-700" id="preview-nohp"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">Email</p>
                                <p class="text-sm text-gray-700" id="preview-email"></p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-400 mb-0.5">Alamat</p>
                                <p class="text-sm text-gray-700" id="preview-alamat"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Data tambahan yang masuk ke tabel koordinator --}}
                    <div class="bg-blue-50 rounded-lg p-4 mb-2">
                        <p class="text-xs text-blue-500 uppercase font-semibold mb-3 tracking-wide">Lengkapi Data Koordinator</p>
                        <div class="grid grid-cols-2 gap-3">

                            {{-- Jenis Kelamin --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                    <option value="">— Pilih —</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            </div>

                            {{-- Foto --}}
                            <div class="col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">
                                    Foto <span class="text-gray-400">(opsional, jpg/png maks. 2MB)</span>
                                </label>
                                <input type="file" name="foto" accept="image/jpg,image/jpeg,image/png"
                                    onchange="previewFoto(this)"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white
                                           file:mr-3 file:py-1 file:px-3 file:rounded file:border-0
                                           file:text-xs file:font-medium file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                                <div id="foto-preview-wrap" class="mt-2 hidden">
                                    <img id="foto-preview-img" src="#" alt="Preview"
                                        class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <div class="flex justify-between items-center p-4 border-t">
                <button onclick="closePreviewModal()"
                    class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                    ← Kembali ke daftar
                </button>
                <button onclick="submitTambahKoor()"
                    class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-5 py-2 rounded-lg">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL DETAIL KOORDINATOR ==================== --}}
<div id="modal-detail-koor" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">
    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Detail Koordinator</h3>
                <button onclick="closeDetailModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>
            <div class="p-5">
                {{-- Foto + Nama + Status --}}
                <div class="flex items-center gap-4 mb-5">
                    <div id="detail-foto-wrap"></div>
                    <div>
                        <p class="text-base font-semibold text-gray-800" id="detail-nama"></p>
                        <div id="detail-status-wrap" class="mt-1"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">NIK</p>
                        <p class="text-sm text-gray-700" id="detail-nik"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">No HP</p>
                        <p class="text-sm text-gray-700" id="detail-nohp"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Jenis Kelamin</p>
                        <p class="text-sm text-gray-700" id="detail-jk"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Tanggal Lahir</p>
                        <p class="text-sm text-gray-700" id="detail-tgl"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-400 mb-1">Alamat</p>
                        <p class="text-sm text-gray-700" id="detail-alamat"></p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end p-4 border-t">
                <button onclick="closeDetailModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL EDIT KOORDINATOR ==================== --}}
<div id="modal-edit-koor" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">
    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Edit Data Koordinator</h3>
                    <p class="text-xs text-gray-400 mt-0.5" id="edit-nama-label"></p>
                </div>
                <button onclick="closeEditModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>

            <div class="px-5 pt-5 pb-3 max-h-[75vh] overflow-y-auto">
                <form id="form-edit-koor" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Data User (read only) --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <p class="text-xs text-gray-400 uppercase font-semibold mb-3 tracking-wide">Data User</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">Nama</p>
                                <p class="text-sm font-semibold text-gray-800" id="edit-display-nama"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">NIK</p>
                                <p class="text-sm text-gray-700" id="edit-display-nik"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">No HP</p>
                                <p class="text-sm text-gray-700" id="edit-display-nohp"></p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-400 mb-0.5">Alamat</p>
                                <p class="text-sm text-gray-700" id="edit-display-alamat"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Field yang bisa diedit --}}
                    <div class="bg-yellow-50 rounded-lg p-4 mb-2">
                        <p class="text-xs text-yellow-600 uppercase font-semibold mb-3 tracking-wide">Edit Data Koordinator</p>
                        <div class="grid grid-cols-2 gap-3">

                            {{-- Jenis Kelamin --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="edit-jk"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                    <option value="">— Pilih —</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="edit-tgl"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            </div>

                            {{-- Status --}}
                            <div class="col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">Status</label>
                                <select name="status" id="edit-status" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                                    <option value="aktif">Aktif</option>
                                    <option value="non-aktif">Non-Aktif</option>
                                </select>
                                <p class="text-xs text-gray-400 mt-1">* Status aktif biasanya diubah otomatis oleh fitur pembagian koordinator.</p>
                            </div>

                            {{-- Foto --}}
                            <div class="col-span-2">
                                <label class="block text-xs text-gray-500 mb-1">
                                    Foto <span class="text-gray-400">(kosongkan jika tidak ingin mengubah)</span>
                                </label>
                                <div id="edit-foto-current" class="mb-2 hidden">
                                    <p class="text-xs text-gray-400 mb-1">Foto saat ini:</p>
                                    <img id="edit-foto-current-img" src="#" alt="Foto saat ini"
                                        class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                                </div>
                                <input type="file" name="foto" accept="image/jpg,image/jpeg,image/png"
                                    onchange="previewFotoEdit(this)"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white
                                           file:mr-3 file:py-1 file:px-3 file:rounded file:border-0
                                           file:text-xs file:font-medium file:bg-yellow-50 file:text-yellow-600 hover:file:bg-yellow-100">
                                <div id="edit-foto-preview-wrap" class="mt-2 hidden">
                                    <p class="text-xs text-gray-400 mb-1">Preview baru:</p>
                                    <img id="edit-foto-preview-img" src="#" alt="Preview"
                                        class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <div class="flex justify-end gap-3 p-4 border-t">
                <button type="button" onclick="closeEditModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">Batal</button>
                <button type="button" onclick="document.getElementById('form-edit-koor').submit()"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg">Update</button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    // ── SEARCH ──
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.searchable-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });

    // ── FILTER STATUS ──
    function filterByStatus(status) {
        const url = new URL(window.location.href);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    }

    // ── MODAL TAMBAH (Step 1) ──
    function openTambahModal()  { document.getElementById('modal-tambah-koor').classList.remove('hidden'); }
    function closeTambahModal() { document.getElementById('modal-tambah-koor').classList.add('hidden'); }

    // ── MODAL PREVIEW (Step 2) ──
    function openPreviewModal(btn) {
        document.getElementById('preview-id-user').value      = btn.dataset.id;
        document.getElementById('preview-nama').textContent   = btn.dataset.nama;
        document.getElementById('preview-nik').textContent    = btn.dataset.nik;
        document.getElementById('preview-nohp').textContent   = btn.dataset.nohp;
        document.getElementById('preview-email').textContent  = btn.dataset.email;
        document.getElementById('preview-alamat').textContent = btn.dataset.alamat;

        // Reset input tambahan
        document.querySelector('[name="jenis_kelamin"]').value = '';
        document.querySelector('[name="tanggal_lahir"]').value = '';
        document.querySelector('[name="foto"]').value = '';
        document.getElementById('foto-preview-wrap').classList.add('hidden');

        document.getElementById('modal-preview-user').classList.remove('hidden');
    }
    function closePreviewModal() { document.getElementById('modal-preview-user').classList.add('hidden'); }

    function submitTambahKoor() { document.getElementById('form-tambah-koor').submit(); }

    // ── PREVIEW FOTO ──
    function previewFoto(input) {
        const wrap = document.getElementById('foto-preview-wrap');
        const img  = document.getElementById('foto-preview-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; wrap.classList.remove('hidden'); };
            reader.readAsDataURL(input.files[0]);
        } else {
            wrap.classList.add('hidden');
        }
    }

    // ── MODAL DETAIL ──
    function openDetailModal(btn) {
        document.getElementById('detail-nama').textContent    = btn.dataset.nama;
        document.getElementById('detail-nik').textContent     = btn.dataset.nik;
        document.getElementById('detail-nohp').textContent    = btn.dataset.nohp;
        document.getElementById('detail-alamat').textContent  = btn.dataset.alamat;

        // Format tanggal lahir dd-mm-yyyy
        const tglRaw = btn.dataset.tgl;
        if (tglRaw) {
            const [y, m, d] = tglRaw.split('-');
            document.getElementById('detail-tgl').textContent = d + '-' + m + '-' + y;
        } else {
            document.getElementById('detail-tgl').textContent = '-';
        }

        const jkMap = { 'L': 'Laki-laki', 'P': 'Perempuan' };
        document.getElementById('detail-jk').textContent = jkMap[btn.dataset.jk] || '-';

        // Status badge
        const statusEl = document.getElementById('detail-status-wrap');
        statusEl.innerHTML = btn.dataset.status === 'aktif'
            ? '<span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>'
            : '<span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">Non-Aktif</span>';

        // Foto
        const fotoWrap = document.getElementById('detail-foto-wrap');
        const avatarSvg = `<div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>`;
        fotoWrap.innerHTML = btn.dataset.foto
            ? `<img src="${btn.dataset.foto}" alt="Foto" class="w-16 h-16 rounded-full object-cover border border-gray-200">`
            : avatarSvg;

        document.getElementById('modal-detail-koor').classList.remove('hidden');
    }
    function closeDetailModal() { document.getElementById('modal-detail-koor').classList.add('hidden'); }

    // ── MODAL EDIT ──
    function openEditModal(btn) {
        document.getElementById('form-edit-koor').action       = `/admin/koordinator/${btn.dataset.id}`;
        document.getElementById('edit-nama-label').textContent = 'Koordinator: ' + btn.dataset.nama;

        // Data user (read only)
        document.getElementById('edit-display-nama').textContent   = btn.dataset.nama;
        document.getElementById('edit-display-nik').textContent    = btn.dataset.nik;
        document.getElementById('edit-display-nohp').textContent   = btn.dataset.nohp;
        document.getElementById('edit-display-alamat').textContent = btn.dataset.alamat;

        // Field editable
        document.getElementById('edit-status').value = btn.dataset.status;
        document.getElementById('edit-jk').value     = btn.dataset.jk || '';
        document.getElementById('edit-tgl').value    = btn.dataset.tgl || '';

        // Reset file input & preview baru
        document.querySelector('#form-edit-koor [name="foto"]').value = '';
        document.getElementById('edit-foto-preview-wrap').classList.add('hidden');

        // Foto saat ini
        const currentWrap = document.getElementById('edit-foto-current');
        const currentImg  = document.getElementById('edit-foto-current-img');
        if (btn.dataset.foto) {
            currentImg.src = btn.dataset.foto;
            currentWrap.classList.remove('hidden');
        } else {
            currentWrap.classList.add('hidden');
        }

        document.getElementById('modal-edit-koor').classList.remove('hidden');
    }
    function closeEditModal() { document.getElementById('modal-edit-koor').classList.add('hidden'); }

    // ── PREVIEW FOTO EDIT ──
    function previewFotoEdit(input) {
        const wrap = document.getElementById('edit-foto-preview-wrap');
        const img  = document.getElementById('edit-foto-preview-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; wrap.classList.remove('hidden'); };
            reader.readAsDataURL(input.files[0]);
        } else {
            wrap.classList.add('hidden');
        }
    }

    // ── TOGGLE DETAIL ROW ──
    function toggleDetail(btn) {
        const row = document.getElementById('detail-' + btn.dataset.id);
        row.querySelector('.detail-email').textContent  = btn.dataset.email;
        row.querySelector('.detail-alamat').textContent = btn.dataset.alamat;
        const isHidden = row.classList.contains('hidden');
        document.querySelectorAll('.detail-row').forEach(r => r.classList.add('hidden'));
        if (isHidden) row.classList.remove('hidden');
    }
</script>

@stop