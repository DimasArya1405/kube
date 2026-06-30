{{-- MEILITA --}}


@extends('pendamping.dashboard.index')

@section('title', 'Data Kunjungan Pendamping - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Data Kunjungan Pendamping</span>
@stop

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Jadwal Kunjungan Pendamping</h2>
        <p class="text-gray-500 mt-1">Kelola seluruh data Jadwal Kunjungan Pendamping KUBE.</p>
    </div>
</div>


{{-- FILTER TAHUN & STATUS (Layout grid horizontal disamakan dengan kode pertama) --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('kunjungan.index') }}" method="GET">

        <div class="flex flex-col md:flex-row gap-4 md:items-end">

            {{-- Tujuan Kunjungan --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tujuan Kunjungan
                </label>

                <select name="tujuan_kunjungan"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[220px] text-sm">

                    <option value="">Semua Tujuan</option>

                    <option value="Monitoring"
                        {{ request('tujuan_kunjungan') == 'Monitoring' ? 'selected' : '' }}>
                        Monitoring
                    </option>

                    <option value="Evaluasi"
                        {{ request('tujuan_kunjungan') == 'Evaluasi' ? 'selected' : '' }}>
                        Evaluasi
                    </option>

                    <option value="Koordinasi"
                        {{ request('tujuan_kunjungan') == 'Koordinasi' ? 'selected' : '' }}>
                        Koordinasi
                    </option>

                    <option value="Kunjungan Rutin"
                        {{ request('tujuan_kunjungan') == 'Kunjungan Rutin' ? 'selected' : '' }}>
                        Kunjungan Rutin
                    </option>

                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status
                </label>

                <select name="status"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[220px] text-sm">

                    <option value="">Semua Status</option>

                    <option value="terjadwal"
                        {{ request('status') == 'terjadwal' ? 'selected' : '' }}>
                        Terjadwal
                    </option>

                    <option value="selesai"
                        {{ request('status') == 'selesai' ? 'selected' : '' }}>
                        Selesai
                    </option>

                </select>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-2">

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                    Filter
                </button>
            </div>

            <a href="{{ route('kunjungan.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                    Reset
                </a>
            <div class="ml-auto">
                
            {{-- Tambah Kunjungan --}}
            <button type="button"
        data-modal-target="modal-tambah-kunjungan"
        data-modal-toggle="modal-tambah-kunjungan"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
    Tambah Kunjungan
</button>   
            </div>
            

        </div>

    </form>
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
            <a href="{{ route('kunjungan.export.pdf') }}"
                class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                Ekspor PDF
            </a>

            {{-- Ekspor Excel --}}
            <a href="{{ route('kunjungan.export.excel') }}"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10" />
                </svg>
                Ekspor Excel
            </a>



</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500" id="koordinatorTable">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama Pendamping</th>
                    <th class="px-4 py-3">Nama Kube</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Kunjungan Ke-</th>
                    <th class="px-4 py-3">Tujuan Kunjungan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjunganPendamping as $item)
                <tr class="border-t border-gray-100 hover:bg-gray-50 searchable-row">

                    {{-- No --}}
                    <td class="px-4 py-3">{{ $loop->iteration }}</td>

                    {{-- Nama Pendamping --}}
                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->pembagian->pendamping->nama_pendamping ?? '-' }}
                    </td>

                    {{-- Nama KUBE --}}
                    <td class="px-4 py-3">{{ $item->pembagian->kube->nama_kube ?? '-' }}
                    </td>

                    {{-- Tanggal --}}
                    <td class="px-4 py-3">{{ date('d-m-Y', strtotime($item->tanggal_kunjungan)) }}
                    </td>

                    {{-- Waktu --}}
                    <td class="px-4 py-3">{{ $item->waktu_kunjungan }}
                    </td>

                    {{-- Kunjungan Ke --}}
                    <td class="px-4 py-3">{{ $item->kunjungan_ke }}
                    </td>

                    {{-- Tujuan --}}
                    <td class="px-4 py-3">
                        @if($item->tujuan_kunjungan == 'Monitoring')
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">Monitoring</span>
                        @elseif($item->tujuan_kunjungan == 'Evaluasi')
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">Evaluasi</span>
                        @elseif($item->tujuan_kunjungan == 'Koordinasi')
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Koordinasi</span>
                        @else
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Kunjungan Rutin</span>
                        @endif
                    </td>
                    {{-- Status --}}
                    <td>
                        @if($item->status == 'terjadwal')
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                            Terjadwal
                        </span>
                        @else
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                            Selesai
                        </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            {{-- Detail --}}
                            <a href="#" data-modal-target="modal-detail-{{ $item->id_kunjungan }}" data-modal-toggle="modal-detail-{{ $item->id_kunjungan }}" class="text-blue-500 hover:text-blue-700" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>

                            {{-- Edit --}}
                            <a href="#" data-modal-target="modal-edit-{{ $item->id_kunjungan }}" data-modal-toggle="modal-edit-{{ $item->id_kunjungan }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Hapus --}}
                            <form action="{{ route('kunjungan.delete', $item->id_kunjungan) }}" method="POST" style="display:inline"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                            {{-- Selesai --}}
                            @if($item->status == 'terjadwal')
                            <button
                                data-modal-target="modalSelesai{{ $item->id_kunjungan }}"
                                data-modal-toggle="modalSelesai{{ $item->id_kunjungan }}"
                                class="text-green-600 hover:text-green-800"
                                title="Tandai Selesai">
                                ✓
                            </button>
                            @endif


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


{{-- MODAL TAMBAH KUNJUNGAN --}}
<div id="modal-tambah-kunjungan" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">

    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">

            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Form Tambah Data Kunjungan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi data Kunjungan dengan lengkap dan benar.</p>
                </div>
                <button type="button" data-modal-toggle="modal-tambah-kunjungan"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">
                    ✕
                </button>
            </div>

            <form action="{{ route('kunjungan.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="p-5">

                @csrf

                <div class="grid grid-cols-2 gap-4 mb-4">

                    {{-- KUBE --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama KUBE
                        </label>

                        <select name="id_pembagian"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>

                            <option value="">-- Pilih KUBE --</option>

                            @foreach($pembagianPendamping as $item)
                            <option value="{{ $item->id_pembagian }}">
                                {{ $item->kube->nama_kube }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                    {{-- TANGGAL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal
                        </label>

                        <input type="date"
                            name="tanggal_kunjungan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>

                    {{-- WAKTU --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Waktu
                        </label>

                        <input type="time"
                            name="waktu_kunjungan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>

                    {{-- KUNJUNGAN KE --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kunjungan Ke-
                        </label>

                        <input type="number"
                            name="kunjungan_ke"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            required>
                    </div>

                </div>

                {{-- TUJUAN --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tujuan Kunjungan
                    </label>

                    <select name="tujuan_kunjungan"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>

                        <option value="">-- Pilih Tujuan --</option>
                        <option value="Monitoring">Monitoring</option>
                        <option value="Evaluasi">Evaluasi</option>
                        <option value="Koordinasi">Koordinasi</option>
                        <option value="Kunjungan Rutin">Kunjungan Rutin</option>

                    </select>
                </div>

                {{-- CATATAN --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Catatan
                    </label>

                    <textarea name="catatan"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-3">

                    <button type="button"
                        data-modal-toggle="modal-tambah-kunjungan"
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

{{-- MODAL EDIT KUNJUNGAN --}}
@foreach($kunjunganPendamping as $item)
<div id="modal-edit-{{ $item->id_kunjungan }}" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">

    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">

            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Form Edit Data Kunjungan</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi data Kunjungan dengan lengkap dan benar.</p>
                </div>
                <button type="button" data-modal-toggle="modal-edit-{{$item->id_kunjungan}}"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">
                    ✕
                </button>
            </div>

            <form action="{{ route('kunjungan.update', $item->id_kunjungan) }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama KUBE</label>
                        <input type="text" id="edit_nama_kube"
                            value="{{ $item->pembagian->kube->nama_kube }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100"
                            readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal_kunjungan" value="{{ $item->tanggal_kunjungan }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                        <input type="time" name="waktu_kunjungan" value="{{ $item->waktu_kunjungan }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kunjungan Ke-</label>
                        <input type="number" name="kunjungan_ke" value="{{ $item->kunjungan_ke }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tujuan Kunjungan
                    </label>

                    <select name="tujuan_kunjungan" class="w-full border px-3 py-2" required>

                        <option value="">-- Pilih Tujuan --</option>

                        <option value="Monitoring"
                            {{ $item->tujuan_kunjungan == 'Monitoring' ? 'selected' : '' }}>
                            Monitoring
                        </option>

                        <option value="Evaluasi"
                            {{ $item->tujuan_kunjungan == 'Evaluasi' ? 'selected' : '' }}>
                            Evaluasi
                        </option>

                        <option value="Koordinasi"
                            {{ $item->tujuan_kunjungan == 'Koordinasi' ? 'selected' : '' }}>
                            Koordinasi
                        </option>

                        <option value="Kunjungan Rutin"
                            {{ $item->tujuan_kunjungan == 'Kunjungan Rutin' ? 'selected' : '' }}>
                            Kunjungan Rutin
                        </option>

                    </select>
                </div>


                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="catatan" rows="3"
                        class="w-full border px-3 py-2">{{ $item->catatan }}</textarea>
                </div>



                <div class="flex justify-end gap-3">
                    <button type="button" data-modal-toggle="modal-edit-{{ $item->id_kunjungan }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-5 py-2 rounded-lg">
                        Edit
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endforeach

{{-- MODAL DETAIL KUNJUNGAN --}}
@foreach($kunjunganPendamping as $item)
<div id="modal-detail-{{ $item->id_kunjungan }}" tabindex="-1"
    class="hidden fixed top-0 left-0 right-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">

    <div class="bg-white rounded-lg shadow w-full max-w-lg p-5">

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Detail Kunjungan</h3>
            <button data-modal-toggle="modal-detail-{{ $item->id_kunjungan }}">✕</button>
        </div>

        <div class="space-y-2 text-sm">

            <div>
                <strong>Nama Pendamping:</strong><br>
                {{ $item->pembagian->pendamping->nama_pendamping }}
            </div>

            <div>
                <strong>Nama KUBE:</strong><br>
                {{ $item->pembagian->kube->nama_kube }}
            </div>

            <div>
                <strong>Tanggal:</strong><br>
                {{ $item->tanggal_kunjungan }}
            </div>

            <div>
                <strong>Waktu:</strong><br>
                {{ $item->waktu_kunjungan }}
            </div>

            <div>
                <strong>Tujuan:</strong><br>
                {{ $item->tujuan_kunjungan }}
            </div>

            <div>
                <strong>Kunjungan Ke:</strong><br>
                {{ $item->kunjungan_ke }}
            </div>

            <div>
                <strong>Catatan:</strong><br>
                {{ $item->catatan ?? '-' }}
            </div>

            <div>
                @if($item->status == 'selesai')

                <div class="mt-4">
                    <p class="font-semibold">Status:</p>
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">
                        Selesai
                    </span>
                </div>

                <div class="mt-4">
                    <p class="font-semibold">Catatan Hasil:</p>
                    <p>{{ $item->catatan_hasil }}</p>
                </div>

                <div class="mt-4">
                    <p class="font-semibold">Bukti Foto:</p>
                    <img src="{{ asset('storage/'.$item->foto_bukti) }}"
                        class="w-48 rounded shadow mt-2">
                </div>

                @endif
            </div>

        </div>

        <div class="mt-4 text-right">
            <button data-modal-toggle="modal-detail-{{ $item->id_kunjungan }}"
                class="bg-gray-500 text-white px-4 py-2 rounded">
                Tutup
            </button>
        </div>

    </div>
</div>
@endforeach

{{-- MODAL SELESAI KUNJUNGAN --}}
@foreach($kunjunganPendamping as $item)
<div id="modalSelesai{{ $item->id_kunjungan }}" tabindex="-1"
    class="hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-50">

    <div class="bg-white rounded-lg shadow p-6 w-full max-w-md">

        <h3 class="text-lg font-semibold mb-4">
            Konfirmasi Kunjungan
        </h3>

        <p class="text-sm mb-3">
            KUBE:
            <strong>{{ $item->pembagian->kube->nama_kube }}</strong>
        </p>

        <form action="{{ route('kunjungan.selesai', $item->id_kunjungan) }}"
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label class="text-sm">Upload Bukti Foto *</label>
                <input type="file" name="foto_bukti" required class="w-full border p-2 rounded">
            </div>

            <div class="mb-3">
                <label class="text-sm">Catatan Hasil</label>
                <textarea name="catatan_hasil" class="w-full border p-2 rounded"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                    data-modal-hide="modalSelesai{{ $item->id_kunjungan }}"
                    class="px-3 py-1 bg-gray-300 rounded">
                    Batal
                </button>

                <button type="submit"
                    class="px-3 py-1 bg-green-600 text-white rounded">
                    Simpan & Selesai
                </button>
            </div>

        </form>

    </div>
</div>
@endforeach


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
</script>


@stop