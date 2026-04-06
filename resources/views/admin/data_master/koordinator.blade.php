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
</div>

{{-- SUMMARY CARDS --}}
<div class="flex gap-4 mb-6">
    <div class="bg-orange-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Koordinator Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $koordinator->where('status','aktif')->count() }}</p>
    </div>
    <div class="bg-green-300 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Koordinator Non-Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $koordinator->where('status','non-aktif')->count() }}</p>
    </div>
</div>

{{-- TOOLBAR: Search + Export + Tambah --}}
<div class="flex flex-wrap items-center gap-3 mb-4">

    {{-- Search --}}
    <div class="relative flex-1 min-w-[200px]">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
            </svg>
        </span>
        <input type="text" id="searchInput" placeholder="Cari..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    {{-- Ekspor PDF --}}
    <a href="#"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        </svg>
        Ekspor PDF
    </a>

    {{-- Ekspor Excel --}}
    <a href="#"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10" />
        </svg>
        Ekspor Excel
    </a>

    {{-- Tambah Koordinator --}}
    <button data-modal-target="modal-tambah-koor" data-modal-toggle="modal-tambah-koor"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + Tambah Koor
    </button>

</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500" id="koordinatorTable">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Nama Koor</th>
                    <th class="px-4 py-3">NIK</th>
                    <th class="px-4 py-3">Jenis Kelamin</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">No HP</th>
                    <th class="px-4 py-3">Tanggal Mulai</th>
                    <th class="px-4 py-3">Kecamatan Binaan</th>
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
                        <img src="{{ asset('storage/foto_koordinator/'.$item->foto) }}" width="40" height="40" class="rounded object-cover" style="width:40px;height:40px;">
                        @else
                        <img src="{{ asset('images/default-user.png') }}" width="40" height="40" class="rounded object-cover" style="width:40px;height:40px;">
                        @endif
                    </td>

                    {{-- Nama --}}
                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->nama_koor }}</td>

                    {{-- NIK --}}
                    <td class="px-4 py-3">{{ $item->nik }}</td>

                    {{-- Jenis Kelamin --}}
                    <td class="px-4 py-3">{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>

                    {{-- Alamat --}}
                    <td class="px-4 py-3">{{ $item->alamat }}</td>

                    {{-- No HP --}}
                    <td class="px-4 py-3">{{ $item->no_hp }}</td>

                    {{-- Tanggal Mulai --}}
                    <td class="px-4 py-3 whitespace-nowrap">{{ $item->tgl_mulai ? date('d-m-Y', strtotime($item->tgl_mulai)) : '-' }}</td>

                    {{-- Kecamatan --}}
                    <td class="px-4 py-3">{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>

                    {{-- Status --}}
                    <td class="px-4 py-3">
                        @if($item->status == 'aktif')
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>
                        @else
                        <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full">Non-Aktif</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            {{-- Detail --}}
                            <a href="#" class="text-blue-500 hover:text-blue-700" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            {{-- Edit --}}
                            <a href="#" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('koordinator.delete', $item->id_koor) }}" method="POST" style="display:inline"
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
                    <td colspan="10" class="px-6 py-8 text-center text-gray-400">Belum ada data koordinator.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- MODAL TAMBAH KOORDINATOR --}}
<div id="modal-tambah-koor" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">

    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">

            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Form Tambah Data Koordinator</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi data koordinator dengan lengkap dan benar.</p>
                </div>
                <button type="button" data-modal-toggle="modal-tambah-koor"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">
                    ✕
                </button>
            </div>

            <form action="{{ route('koordinator.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Koordinator</label>
                    <input type="text" name="nama_koor"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                        <input type="text" name="no_hp"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <div class="flex gap-4 mt-1">
                        <label class="flex items-center gap-2 text-sm text-blue-600 font-medium">
                            <input type="radio" name="jenis_kelamin" value="L" class="accent-blue-600"> Laki-laki
                        </label>
                        <label class="flex items-center gap-2 text-sm text-blue-600 font-medium">
                            <input type="radio" name="jenis_kelamin" value="P" class="accent-blue-600"> Perempuan
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tgl_mulai"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan Binaan</label>
                        <select name="id_kecamatan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Pilih Kecamatan</option>
                            @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                        <input type="file" name="foto" id="fotoInput" class="hidden" accept="image/*">
                        <input type="text" id="fotoLabel" placeholder="" readonly
                            class="flex-1 px-3 py-2 text-sm text-gray-500 bg-white outline-none cursor-pointer"
                            onclick="document.getElementById('fotoInput').click()">
                        <button type="button" onclick="document.getElementById('fotoInput').click()"
                            class="bg-teal-500 hover:bg-teal-600 text-white text-sm px-4 py-2 whitespace-nowrap">
                            Pilih File
                        </button>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" data-modal-toggle="modal-tambah-koor"
                        class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-5 py-2 rounded-lg">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>


{{-- SCRIPT: Search --}}
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('.searchable-row');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });

    // Show filename on file input change
    document.getElementById('fotoInput').addEventListener('change', function() {
        const label = document.getElementById('fotoLabel');
        label.value = this.files[0] ? this.files[0].name : '';
    });
</script>

@stop