{{-- MEILITA --}}


@extends('admin.layout')

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
    <form action="{{ route('admin.kunjungan.index') }}" method="GET">

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

            <a href="{{ route('admin.kunjungan.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                    Reset
                </a>
            <div class="ml-auto">
                
            {{-- Tambah Kunjungan --}}
            <button data-modal-target="modal-tambah-kunjungan" data-modal-toggle="modal-tambah-kunjungan"
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