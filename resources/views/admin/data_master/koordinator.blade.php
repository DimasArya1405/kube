{{-- KATRINA --}}
@extends('admin.layout')

@section('title', 'Data Koordinator - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Data Koordinator</span>
@stop

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Koordinator</h2>
        <p class="text-gray-500 mt-1">Kelola seluruh data koordinator KUBE.</p>
    </div>
    <button type="button" onclick="openTambahModal()"
        class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition shadow-sm font-medium">
        Tambah Koordinator
    </button>
</div>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded-lg shadow border border-blue-200">
        <p class="text-sm text-blue-600 font-medium">Koordinator Aktif</p>
        <h3 class="text-2xl font-bold text-blue-700">{{ $koordinator->where('status','Aktif')->count() }}</h3>
    </div>
    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600 font-medium">Koordinator Tidak Aktif</p>
        <h3 class="text-2xl font-bold text-red-700">{{ $koordinator->where('status','Tidak Aktif')->count() }}</h3>
    </div>
</div>

{{-- 🛠️ TOOLBAR & FILTER (Card terpisah, mengikuti gaya Pencairan Bantuan) --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">

        {{-- Search (sebelah kiri) --}}
        <div class="relative w-full flex-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
            </span>
            <input type="text" id="searchInput" placeholder="Cari nama, NIK..."
                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Filter & Aksi (kanan) --}}
        <div class="flex items-center gap-2 w-full md:w-auto justify-end shrink-0">
            <select id="filterStatus" onchange="filterByStatus(this.value)"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>

            <a href="{{ route('koordinator.export.pdf') }}{{ request('status') ? '?status='.request('status') : '' }}"
                class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
                Ekspor PDF
            </a>

            <a href="{{ route('koordinator.export.excel') }}{{ request('status') ? '?status='.request('status') : '' }}"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition shadow-sm">
                Ekspor Excel
            </a>
        </div>

    </div>
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

                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->nama_koordinator }}</td>
                    <td class="px-4 py-3">{{ $item->nik }}</td>
                    <td class="px-4 py-3">{{ $item->no_hp }}</td>

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

                    {{-- Status: Aktif = biru, Tidak Aktif = merah --}}
                    <td class="px-4 py-3">
                        @if($item->status == 'Aktif')
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>
                        @else
                            <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">Tidak Aktif</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">

                            {{-- Detail --}}
                            <button
                                data-id="{{ $item->id_koor }}"
                                data-nama="{{ $item->nama_koordinator }}"
                                data-nik="{{ $item->nik }}"
                                data-nohp="{{ $item->no_hp }}"
                                data-email="{{ $item->email }}"
                                data-alamat="{{ $item->alamat }}"
                                data-jk="{{ $item->jenis_kelamin ?? '' }}"
                                data-tempatlahir="{{ $item->tempat_lahir ?? '' }}"
                                data-tgl="{{ $item->tanggal_lahir ?? '' }}"
                                data-pendidikan="{{ $item->pendidikan_terakhir ?? '' }}"
                                data-idkecamatan="{{ $item->id_kecamatan ?? '' }}"
                                data-namakecamatan="{{ $item->kecamatan->nama_kecamatan ?? '-' }}"
                                data-namadesa="{{ $item->desa->nama_desa_kelurahan ?? '-' }}"
                                data-wilayah="{{ $item->wilayah ?? '-' }}"
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
                                data-nama="{{ $item->nama_koordinator }}"
                                data-nik="{{ $item->nik }}"
                                data-namakoor="{{ $item->nama_koordinator }}"
                                data-nohp="{{ $item->no_hp }}"
                                data-email="{{ $item->email }}"
                                data-alamat="{{ $item->alamat }}"
                                data-status="{{ $item->status }}"
                                data-jk="{{ $item->jenis_kelamin ?? '' }}"
                                data-tempatlahir="{{ $item->tempat_lahir ?? '' }}"
                                data-tgl="{{ $item->tanggal_lahir ?? '' }}"
                                data-pendidikan="{{ $item->pendidikan_terakhir ?? '' }}"
                                data-idkecamatan="{{ $item->id_kecamatan ?? '' }}"
                                data-iddesa="{{ $item->id_desa_kelurahan ?? '' }}"
                                data-wilayah="{{ $item->wilayah ?? '' }}"
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

{{-- ==================== MODAL TAMBAH ==================== --}}
<div id="modal-tambah-koor" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">

    <div class="fixed inset-0" onclick="closeTambahModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">

        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Koordinator</h3>
            <button type="button" onclick="closeTambahModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
            <form id="form-tambah-koor" action="{{ route('koordinator.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Pilih User --}}
                <div class="mb-4">
                    <label class="block text-xs text-gray-500 mb-1">
                        Pilih User <span class="text-red-400">*</span>
                    </label>
                    <select name="id_user" id="tambah-id-user" required onchange="autoFillFromUser(this)"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                        <option value="">— Pilih user koordinator —</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id_user }}"
                                data-nik="{{ $user->nik ?? '' }}"
                                data-nama="{{ $user->nama }}"
                                data-nohp="{{ $user->no_hp ?? '' }}"
                                data-email="{{ $user->email ?? '' }}"
                                data-alamat="{{ $user->alamat ?? '' }}"
                                data-idkecamatan="{{ $user->id_kecamatan ?? '' }}"
                                data-iddesa="{{ $user->id_desa_kelurahan ?? '' }}">
                                {{ $user->nama }} — {{ $user->nik ?? 'NIK belum diisi' }}
                            </option>
                        @endforeach
                    </select>
                    @if($users->isEmpty())
                        <p class="text-xs text-orange-400 mt-1">Tidak ada user koordinator yang belum diproses.</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3">

                    {{-- NIK --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">NIK <span class="text-red-400">*</span></label>
                        <input type="text" name="nik" id="tambah-nik" maxlength="16" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="16 digit NIK">
                    </div>

                    {{-- Nama Koordinator --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Nama Koordinator <span class="text-red-400">*</span></label>
                        <input type="text" name="nama_koordinator" id="tambah-nama" maxlength="100" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Nama lengkap">
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jenis Kelamin <span class="text-red-400">*</span></label>
                        <select name="jenis_kelamin" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="">— Pilih —</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tempat Lahir <span class="text-red-400">*</span></label>
                        <input type="text" name="tempat_lahir" maxlength="50" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Kota tempat lahir">
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Lahir <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal_lahir" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">No HP <span class="text-red-400">*</span></label>
                        <input type="text" name="no_hp" id="tambah-nohp" maxlength="15" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="08xx">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" id="tambah-email" maxlength="100" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="email@contoh.com">
                    </div>

                    {{-- Pendidikan Terakhir --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Pendidikan Terakhir <span class="text-red-400">*</span></label>
                        <select name="pendidikan_terakhir" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="">— Pilih —</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    {{-- Kecamatan --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Kecamatan <span class="text-red-400">*</span></label>
                        <select name="id_kecamatan" id="tambah-kecamatan" required onchange="loadDesaTambah(this.value)"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="">— Pilih kecamatan —</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Desa/Kelurahan --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Desa/Kelurahan</label>
                        <select name="id_desa_kelurahan" id="tambah-desa" disabled
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-100 text-gray-600">
                            <option value="">— Pilih kecamatan dahulu —</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status <span class="text-red-400">*</span></label>
                        <select name="status" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    {{-- Wilayah --}}
                    <div class="col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Wilayah</label>
                        <input type="text" name="wilayah" id="tambah-wilayah" maxlength="100"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Nama wilayah">
                    </div>

                    {{-- Alamat --}}
                    <div class="col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Alamat <span class="text-red-400">*</span></label>
                        <textarea name="alamat" id="tambah-alamat" rows="2" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Alamat lengkap"></textarea>
                    </div>

                    {{-- Foto (opsional) --}}
                    <div class="col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">
                            Foto <span class="text-gray-400">(opsional, jpg/png maks. 2MB)</span>
                        </label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                            <input type="file" name="foto" id="tambahFoto" class="hidden" accept="image/jpg,image/jpeg,image/png" onchange="previewFoto(this)">
                            <input type="text" id="tambahFotoLabel" readonly
                                class="flex-1 px-3 py-2 text-sm text-gray-500 cursor-pointer focus:outline-none"
                                placeholder="Belum ada file dipilih"
                                onclick="document.getElementById('tambahFoto').click()">
                            <button type="button"
                                onclick="document.getElementById('tambahFoto').click()"
                                class="bg-gray-400 hover:bg-gray-300 text-black text-sm px-4 py-2 transition font-medium">
                                Pilih File
                            </button>
                        </div>
                        <div id="foto-preview-wrap" class="mt-2 hidden">
                            <img id="foto-preview-img" src="#" alt="Preview"
                                class="h-20 w-20 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
            <button type="button" onclick="closeTambahModal()"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">Batal</button>
            <button type="button" onclick="document.getElementById('form-tambah-koor').submit()"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium">Simpan</button>
        </div>
    </div>
</div>

{{-- ==================== MODAL DETAIL KOORDINATOR ==================== --}}
<div id="modal-detail-koor" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">

    <div class="fixed inset-0" onclick="closeDetailModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] overflow-hidden flex flex-col z-10">

        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Detail Koordinator</h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
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
                    <p class="text-xs text-gray-400 mb-1">Tempat, Tanggal Lahir</p>
                    <p class="text-sm text-gray-700" id="detail-ttl"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Email</p>
                    <p class="text-sm text-gray-700" id="detail-email"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Pendidikan Terakhir</p>
                    <p class="text-sm text-gray-700" id="detail-pendidikan"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Kecamatan</p>
                    <p class="text-sm text-gray-700" id="detail-kecamatan"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Desa/Kelurahan</p>
                    <p class="text-sm text-gray-700" id="detail-desa"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Wilayah</p>
                    <p class="text-sm text-gray-700" id="detail-wilayah"></p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-400 mb-1">Alamat</p>
                    <p class="text-sm text-gray-700" id="detail-alamat"></p>
                </div>
            </div>
        </div>

        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="button" onclick="closeDetailModal()"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">Tutup</button>
        </div>
    </div>
</div>

{{-- ==================== MODAL EDIT ==================== --}}
<div id="modal-edit-koor" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">

    <div class="fixed inset-0" onclick="closeEditModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">

        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Edit Data Koordinator</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
            <p class="text-xs text-gray-400 mb-4" id="edit-nama-label"></p>

            <form id="form-edit-koor" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-3">

                    {{-- NIK --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">NIK <span class="text-red-400">*</span></label>
                        <input type="text" name="nik" id="edit-nik" maxlength="16" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- Nama Koordinator --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Nama Koordinator <span class="text-red-400">*</span></label>
                        <input type="text" name="nama_koordinator" id="edit-nama-koor" maxlength="100" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Jenis Kelamin <span class="text-red-400">*</span></label>
                        <select name="jenis_kelamin" id="edit-jk" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="">— Pilih —</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    {{-- Tempat Lahir --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tempat Lahir <span class="text-red-400">*</span></label>
                        <input type="text" name="tempat_lahir" id="edit-tempat-lahir" maxlength="50" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Tanggal Lahir <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal_lahir" id="edit-tgl" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">No HP <span class="text-red-400">*</span></label>
                        <input type="text" name="no_hp" id="edit-nohp" maxlength="15" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Email <span class="text-red-400">*</span></label>
                        <input type="email" name="email" id="edit-email" maxlength="100" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- Pendidikan Terakhir --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Pendidikan Terakhir <span class="text-red-400">*</span></label>
                        <select name="pendidikan_terakhir" id="edit-pendidikan" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="">— Pilih —</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA/SMK">SMA/SMK</option>
                            <option value="D3">D3</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>

                    {{-- Kecamatan --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Kecamatan <span class="text-red-400">*</span></label>
                        <select name="id_kecamatan" id="edit-kecamatan" required onchange="loadDesaEdit(this.value)"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="">— Pilih kecamatan —</option>
                            @foreach($kecamatan as $kec)
                                <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Desa/Kelurahan --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Desa/Kelurahan</label>
                        <select name="id_desa_kelurahan" id="edit-desa" disabled
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-100 text-gray-600">
                            <option value="">— Pilih kecamatan dahulu —</option>
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Status <span class="text-red-400">*</span></label>
                        <select name="status" id="edit-status" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    {{-- Wilayah --}}
                    <div class="col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Wilayah</label>
                        <input type="text" name="wilayah" id="edit-wilayah" maxlength="100"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    {{-- Alamat --}}
                    <div class="col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">Alamat <span class="text-red-400">*</span></label>
                        <textarea name="alamat" id="edit-alamat" rows="2" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                    </div>

                    {{-- Foto (opsional) --}}
                    <div class="col-span-2">
                        <label class="block text-xs text-gray-500 mb-1">
                            Ganti Foto <span class="text-gray-400">(kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <div id="edit-foto-current" class="mb-2 hidden">
                            <p class="text-xs text-gray-400 mb-1">Foto saat ini:</p>
                            <img id="edit-foto-current-img" src="#" alt="Foto saat ini"
                                class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                        </div>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                            <input type="file" name="foto" id="editFoto" class="hidden" accept="image/jpg,image/jpeg,image/png" onchange="previewFotoEdit(this)">
                            <input type="text" id="editFotoLabel" readonly
                                class="flex-1 px-3 py-2 text-sm text-gray-500 cursor-pointer focus:outline-none"
                                placeholder="Belum ada file dipilih"
                                onclick="document.getElementById('editFoto').click()">
                            <button type="button"
                                onclick="document.getElementById('editFoto').click()"
                                class="bg-gray-400 hover:bg-gray-300 text-black text-sm px-4 py-2 transition font-medium">
                                Pilih File
                            </button>
                        </div>
                        <div id="edit-foto-preview-wrap" class="mt-2 hidden">
                            <p class="text-xs text-gray-400 mb-1">Preview baru:</p>
                            <img id="edit-foto-preview-img" src="#" alt="Preview"
                                class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>

                </div>
            </form>
        </div>

        <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
            <button type="button" onclick="closeEditModal()"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">Batal</button>
            <button type="button" onclick="document.getElementById('form-edit-koor').submit()"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium">Simpan</button>
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
        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');
        window.location.href = url.toString();
    }

    // ── MODAL TAMBAH ──
    function openTambahModal() {
        document.getElementById('form-tambah-koor').reset();
        document.getElementById('tambahFotoLabel').value = '';
        document.getElementById('foto-preview-wrap').classList.add('hidden');
        document.getElementById('tambah-desa').innerHTML = '<option value="">— Pilih kecamatan dahulu —</option>';
        document.getElementById('tambah-desa').disabled = true;
        document.getElementById('modal-tambah-koor').classList.remove('hidden');
    }
    function closeTambahModal() {
        document.getElementById('modal-tambah-koor').classList.add('hidden');
    }

    // Auto-fill dari pilihan user
    function autoFillFromUser(select) {
        const opt = select.options[select.selectedIndex];
        document.getElementById('tambah-nik').value       = opt.dataset.nik       || '';
        document.getElementById('tambah-nama').value      = opt.dataset.nama      || '';
        document.getElementById('tambah-nohp').value      = opt.dataset.nohp      || '';
        document.getElementById('tambah-email').value     = opt.dataset.email     || '';
        document.getElementById('tambah-alamat').value    = opt.dataset.alamat    || '';
        document.getElementById('tambah-kecamatan').value = opt.dataset.idkecamatan || '';

        const idDesaUser = opt.dataset.iddesa || '';
        if (opt.dataset.idkecamatan) {
            loadDesaTambah(opt.dataset.idkecamatan, idDesaUser);
        }
    }

    // ── DESA DINAMIS (TAMBAH) ──
    function loadDesaTambah(idKecamatan, selectedIdDesa = null) {
        const selectDesa = document.getElementById('tambah-desa');
        selectDesa.innerHTML = '<option value="">Memuat...</option>';

        if (!idKecamatan) {
            selectDesa.disabled = true;
            selectDesa.classList.add('bg-gray-100');
            selectDesa.innerHTML = '<option value="">— Pilih kecamatan dahulu —</option>';
            return;
        }

        fetch(`/get-desa/${idKecamatan}`)
            .then(res => res.json())
            .then(data => {
                selectDesa.innerHTML = '<option value="">— Pilih desa/kelurahan —</option>';
                data.forEach(desa => {
                    const option = document.createElement('option');
                    option.value = desa.id_desa_kelurahan;
                    option.textContent = desa.nama_desa_kelurahan;
                    if (selectedIdDesa && desa.id_desa_kelurahan == selectedIdDesa) {
                        option.selected = true;
                    }
                    selectDesa.appendChild(option);
                });
                selectDesa.disabled = false;
                selectDesa.classList.remove('bg-gray-100');
            })
            .catch(() => {
                selectDesa.innerHTML = '<option value="">Gagal memuat desa</option>';
            });
    }

    // ── PILIH & PREVIEW FOTO TAMBAH ──
    document.getElementById('tambahFoto').addEventListener('change', function () {
        document.getElementById('tambahFotoLabel').value = this.files[0] ? this.files[0].name : '';
    });
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
        document.getElementById('detail-nama').textContent       = btn.dataset.nama;
        document.getElementById('detail-nik').textContent        = btn.dataset.nik;
        document.getElementById('detail-nohp').textContent       = btn.dataset.nohp;
        document.getElementById('detail-email').textContent      = btn.dataset.email      || '-';
        document.getElementById('detail-pendidikan').textContent = btn.dataset.pendidikan || '-';
        document.getElementById('detail-kecamatan').textContent  = btn.dataset.namakecamatan || '-';
        document.getElementById('detail-desa').textContent       = btn.dataset.namadesa || '-';
        document.getElementById('detail-wilayah').textContent    = btn.dataset.wilayah || '-';
        document.getElementById('detail-alamat').textContent     = btn.dataset.alamat;

        // Tempat, Tanggal Lahir
        const tglRaw      = btn.dataset.tgl;
        const tempatLahir = btn.dataset.tempatlahir || '-';
        if (tglRaw) {
            const [y, m, d] = tglRaw.split('-');
            document.getElementById('detail-ttl').textContent = tempatLahir + ', ' + d + '-' + m + '-' + y;
        } else {
            document.getElementById('detail-ttl').textContent = tempatLahir;
        }

        const jkMap = { 'L': 'Laki-laki', 'P': 'Perempuan' };
        document.getElementById('detail-jk').textContent = jkMap[btn.dataset.jk] || '-';

        // Status badge: Aktif = biru, Tidak Aktif = merah
        const statusEl = document.getElementById('detail-status-wrap');
        statusEl.innerHTML = btn.dataset.status === 'Aktif'
            ? '<span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>'
            : '<span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">Tidak Aktif</span>';

        // Foto
        const fotoWrap  = document.getElementById('detail-foto-wrap');
        const avatarSvg = `<div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg></div>`;
        fotoWrap.innerHTML = btn.dataset.foto
            ? `<img src="${btn.dataset.foto}" alt="Foto" class="w-16 h-16 rounded-full object-cover border border-gray-200">`
            : avatarSvg;

        document.getElementById('modal-detail-koor').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('modal-detail-koor').classList.add('hidden');
    }

    // ── MODAL EDIT ──
    function openEditModal(btn) {
        document.getElementById('form-edit-koor').action       = `/admin/koordinator/${btn.dataset.id}`;
        document.getElementById('edit-nama-label').textContent = 'Koordinator: ' + btn.dataset.nama;

        document.getElementById('edit-nik').value          = btn.dataset.nik          || '';
        document.getElementById('edit-nama-koor').value    = btn.dataset.namakoor     || '';
        document.getElementById('edit-jk').value           = btn.dataset.jk           || '';
        document.getElementById('edit-tempat-lahir').value = btn.dataset.tempatlahir  || '';
        document.getElementById('edit-tgl').value          = btn.dataset.tgl          || '';
        document.getElementById('edit-nohp').value         = btn.dataset.nohp         || '';
        document.getElementById('edit-email').value        = btn.dataset.email        || '';
        document.getElementById('edit-pendidikan').value   = btn.dataset.pendidikan   || '';
        document.getElementById('edit-kecamatan').value    = btn.dataset.idkecamatan  || '';
        document.getElementById('edit-wilayah').value      = btn.dataset.wilayah      || '';
        document.getElementById('edit-status').value       = btn.dataset.status       || 'Aktif';
        document.getElementById('edit-alamat').value       = btn.dataset.alamat       || '';

        // Load desa berdasarkan kecamatan, lalu pilih desa yang sesuai
        if (btn.dataset.idkecamatan) {
            loadDesaEdit(btn.dataset.idkecamatan, btn.dataset.iddesa || null);
        } else {
            document.getElementById('edit-desa').innerHTML = '<option value="">— Pilih kecamatan dahulu —</option>';
            document.getElementById('edit-desa').disabled = true;
        }

        // Reset file & label & preview baru
        document.getElementById('editFoto').value = '';
        document.getElementById('editFotoLabel').value = '';
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
    function closeEditModal() {
        document.getElementById('modal-edit-koor').classList.add('hidden');
    }

    // ── DESA DINAMIS (EDIT) ──
    function loadDesaEdit(idKecamatan, selectedIdDesa = null) {
        const selectDesa = document.getElementById('edit-desa');
        selectDesa.innerHTML = '<option value="">Memuat...</option>';

        if (!idKecamatan) {
            selectDesa.disabled = true;
            selectDesa.classList.add('bg-gray-100');
            selectDesa.innerHTML = '<option value="">— Pilih kecamatan dahulu —</option>';
            return;
        }

        fetch(`/get-desa/${idKecamatan}`)
            .then(res => res.json())
            .then(data => {
                selectDesa.innerHTML = '<option value="">— Pilih desa/kelurahan —</option>';
                data.forEach(desa => {
                    const option = document.createElement('option');
                    option.value = desa.id_desa_kelurahan;
                    option.textContent = desa.nama_desa_kelurahan;
                    if (selectedIdDesa && desa.id_desa_kelurahan == selectedIdDesa) {
                        option.selected = true;
                    }
                    selectDesa.appendChild(option);
                });
                selectDesa.disabled = false;
                selectDesa.classList.remove('bg-gray-100');
            })
            .catch(() => {
                selectDesa.innerHTML = '<option value="">Gagal memuat desa</option>';
            });
    }

    // ── PILIH & PREVIEW FOTO EDIT ──
    document.getElementById('editFoto').addEventListener('change', function () {
        document.getElementById('editFotoLabel').value = this.files[0] ? this.files[0].name : '';
    });
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
</script>

@stop