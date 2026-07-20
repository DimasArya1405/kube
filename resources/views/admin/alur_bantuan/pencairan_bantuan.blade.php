@extends('admin.layout')

@section('title', 'Data Pencairan Bantuan - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Pencairan Bantuan</span>
@stop

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Pencairan Bantuan</h2>
        <p class="text-gray-500 mt-1">Kelola data pencairan bantuan KUBE.</p>
    </div>
    <div>
        <button type="button" onclick="toggleModal('modal-tambah')"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition shadow-sm font-medium">
            Tambah Pencairan
        </button>
    </div>
</div>

{{-- 🔥 SUMMARY BOX (Disamakan persis dengan kode pertama) --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    {{-- Total Pencairan --}}
    <div class="bg-white p-4 rounded-lg shadow border">
        <p class="text-sm text-gray-500">Sudah cair</p>
        <h3 class="text-2xl font-bold text-gray-800">
            {{ $total_cair ?? 0 }}
        </h3>
    </div>

    {{-- Menunggu --}}
    <div class="bg-yellow-50 p-4 rounded-lg shadow border border-yellow-200">
        <p class="text-sm text-yellow-600">Menunggu</p>
        <h3 class="text-2xl font-bold text-yellow-700">
            {{ $total_menunggu ?? 0 }}
        </h3>
    </div>

    {{-- Disetujui --}}
    <div class="bg-green-50 p-4 rounded-lg shadow border border-green-200">
        <p class="text-sm text-green-600">Disetujui</p>
        <h3 class="text-2xl font-bold text-green-700">
            {{ $total_disetujui ?? 0 }}
        </h3>
    </div>

    {{-- Ditolak --}}
    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600">Ditolak</p>
        <h3 class="text-2xl font-bold text-red-700">
            {{ $total_ditolak ?? 0 }}
        </h3>
    </div>
</div>

{{-- FILTER TAHUN & STATUS (Layout grid horizontal disamakan dengan kode pertama) --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('admin.pencairan_bantuan.index') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Tahun</label>
                <select name="tahun"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-200px text-sm">
                    <option value="">Semua Tahun</option>
                    @php
                        $tahunSekarang = date('Y');
                    @endphp
                    @for ($i = $tahunSekarang; $i >= $tahunSekarang - 5; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-200px text-sm">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="cair" {{ request('status') == 'cair' ? 'selected' : '' }}>Cair</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                    Filter
                </button>

                <a href="{{ route('admin.pencairan_bantuan.index') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

@if(request('tahun') || request('status'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan data filter: 
        @if(request('tahun')) Tahun <span class="font-semibold text-gray-800">{{ request('tahun') }}</span> @endif
        @if(request('status')) Status <span class="font-semibold text-gray-800">{{ ucfirst(request('status')) }}</span> @endif
    </div>
@endif

{{-- TABEL UTAMA --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama Kube</th>
                    <th class="px-6 py-3">Desa / Kelurahan</th>
                    <th class="px-6 py-3">Cluster Usaha</th>
                    <th class="px-6 py-3">Total Pencairan</th>
                    <th class="px-6 py-3">Total Nilai Bantuan (Rp)</th>
                    <th class="px-6 py-3">Pencairan Terakhir</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kube_pencairan as $i => $row)
                <tr class="border-b hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">{{ $i + 1 }}</td>
                    
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $row->nama_kube ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $row->desa->nama_desa_kelurahan ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $row->clusterUsaha->nama_cluster ?? '-' }}
                    </td>

                    <td class="px-6 py-4 font-semibold text-gray-900">
                        {{ $row->total_pencairan }} pencairan
                    </td>

                    <td class="px-6 py-4 font-mono text-gray-900">
                        {{ number_format($row->total_nilai_bantuan, 0, ',', '.') }}
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $row->pencairan_terakhir ? \Carbon\Carbon::parse($row->pencairan_terakhir)->locale('id')->translatedFormat('d F Y') : '-' }}
                    </td>

                    <td class="px-6 py-4">
                        <a href="{{ route('admin.pencairan_bantuan.detail', ['id_kube' => $row->id_kube, 'tahun' => request('tahun'), 'status' => request('status')]) }}"
                            class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-500 italic">
                        Belum ada data KUBE.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL TAMBAH (Struktur disamakan persis dengan perbaikan kode pertama) ================= --}}
<div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    <div class="fixed inset-0" onclick="toggleModal('modal-tambah')"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col z-10">

        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Daftar Pengajuan Bantuan</h3>
            <button type="button" onclick="toggleModal('modal-tambah')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
            <table class="w-full text-sm text-left text-gray-500 border-collapse">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 border-b">Nama Kube</th>
                        <th class="px-4 py-3 border-b">Jenis Bantuan</th>
                        <th class="px-4 py-3 border-b">Nilai (Rp)</th>
                        <th class="px-4 py-3 border-b">Tanggal Pengajuan</th>
                        <th class="px-4 py-3 border-b">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($pengajuan_bantuan as $i => $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4 font-medium text-gray-900">{{ $row->kube->nama_kube ?? '-' }}</td>
                            <td class="px-4 py-4">{{ $row->jenisBantuan->jenis_bantuan ?? '-' }}</td>
                            <td class="px-4 py-4 font-mono">{{ number_format($row->jumlah_bantuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->locale('id')->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-4 py-4">
                                {{-- <form action="{{ route('admin.pencairan_bantuan.tambah', $row->id_pengajuan_kube) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah anda yakin ingin membuat pencairan untuk {{ $row->kube->nama_kube }}?')">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1 rounded-md text-sm bg-green-500 text-white hover:bg-green-700 transition duration-200 ease-in-out">
                                        Buat Pencairan
                                    </button>
                                </form> --}}
                               
    <button type="button" 
        onclick="bukaKonfirmasiPencairan('{{ $row->id_pengajuan_kube }}', '{{ $row->kube->nama_kube ?? '-' }}', '{{ route('admin.pencairan_bantuan.tambah', $row->id_pengajuan_kube) }}')"
        class="px-3 py-1 rounded-md text-sm bg-green-500 text-white hover:bg-green-700 transition duration-200 ease-in-out">
        Buat Pencairan
    </button>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                Belum ada data pengajuan KUBE.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="button" onclick="toggleModal('modal-tambah')"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Tutup
            </button>
        </div>
    </div>

        <!-- <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="button" onclick="toggleModal('modal-tambah')"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Tutup
            </button>
        </div> -->
    </div>
</div>

{{-- SUB MODAL --}}
{{-- ================= SUB-MODAL PILIH TAHAP ================= --}}
<div id="modal-pilih-tahap" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-gray-950/40 p-4 backdrop-blur-xs">
    <div class="fixed inset-0" onclick="toggleModal('modal-pilih-tahap')"></div>
    
    <div class="relative bg-white rounded-lg shadow-2xl w-full max-w-md z-10 p-6 border animate-fade-in">
        <h4 class="text-lg font-bold text-gray-800 mb-2">Pilih Tahap Pencairan</h4>
        <p class="text-sm text-gray-500 mb-4">Silakan tentukan tahap pencairan untuk KUBE: <span id="text-nama-kube" class="font-semibold text-gray-700"></span></p>
        
        <form id="form-pencairan-tahap" action="" method="POST">
            @csrf
            
            <label class="block text-sm font-medium text-gray-700 mb-2">Tahap Pencairan</label>
            <div class="grid grid-cols-3 gap-3 mb-6">
                <label class="flex items-center justify-center gap-2 border rounded-lg p-3 cursor-pointer hover:bg-gray-50 peer-checked:border-blue-500 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                    <input type="radio" name="tahap" value="1" required class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-900">Tahap 1</span>
                </label>

                <label class="flex items-center justify-center gap-2 border rounded-lg p-3 cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                    <input type="radio" name="tahap" value="2" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-900">Tahap 2</span>
                </label>

                <label class="flex items-center justify-center gap-2 border rounded-lg p-3 cursor-pointer hover:bg-gray-50 has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                    <input type="radio" name="tahap" value="3" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-900">Tahap 3</span>
                </label>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modal-pilih-tahap')" 
                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition shadow-sm font-medium">
                    Proses Pencairan
                </button>
            </div>
        </form>
    </div>
</div>
{{-- SUB MODAL --}}

@push('scripts')
    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        function bukaKonfirmasiPencairan(id, namaKube, urlAction) {
            // Set text nama kube di sub-modal
            document.getElementById('text-nama-kube').innerText = namaKube;
            
            // Set action URL form secara dinamis
            const form = document.getElementById('form-pencairan-tahap');
            form.setAttribute('action', urlAction);
            
            // Reset pilihan radio button sebelumnya (jika ada)
            form.reset();
            
            // Tampilkan sub-modal pilih tahap
            toggleModal('modal-pilih-tahap');
        }
    </script>
@endpush
@stop
