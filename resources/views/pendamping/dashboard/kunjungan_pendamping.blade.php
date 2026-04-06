{{-- KATRINA --}}

@extends('admin.layout')

@section('title', 'Data Kunjungan Pendamping - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Data Kunjungan Pendamping</span>
@stop

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Kunjungan Pendamping</h2>
        <p class="text-gray-500 mt-1">Kelola seluruh data Kunjungan Pendamping KUBE.</p>
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

    {{-- Tambah Kunjungan --}}
    <button data-modal-target="modal-tambah-kunjungan" data-modal-toggle="modal-tambah-kunjungan"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + Tambah Kunjungan
    </button>

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

            <form action="#" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Pendamping
                    </label>

                    <select name="id_pembagian" id="pembagian"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required>

                        <option value="">-- Pilih Pendamping --</option>

                        @foreach($pembagianPendamping as $item)
                            <option 
                                value="{{ $item->id_pembagian }}"
                                data-kube="{{ $item->kube->nama_kube }}"
                            >
                                {{ $item->pendamping->nama_pendamping }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama KUBE</label>
                    <input type="text" id="nama_kube"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100"
                        readonly>
                </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal_kunjungan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                        <input type="time" name="waktu_kunjungan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kunjungan Ke-</label>
                        <input type="number" name="kunjungan_ke"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    </div>
                    
                </div>
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

                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="alamat" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

               

                <div class="flex justify-end gap-3">
                    <button type="button" data-modal-toggle="modal-tambah-kunjungan"
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

    // 
    document.getElementById('pembagian').addEventListener('change', function() {
        let selected = this.options[this.selectedIndex];
        let kube = selected.getAttribute('data-kube');

        document.getElementById('nama_kube').value = kube ?? '';
    });
</script>


@stop