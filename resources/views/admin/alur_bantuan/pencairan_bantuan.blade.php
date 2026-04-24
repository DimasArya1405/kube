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
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.alur_bantuan.jenis_bantuan.index') }}"
                class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md">
                Olah Data Jenis Bantuan
            </a>
            <button data-modal-target="modal-tambah" data-modal-toggle="modal-tambah"
                class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md">
                Tambah Pencairan
            </button>
        </div>
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
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px] text-sm">
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
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[200px] text-sm">
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
                    <th class="px-6 py-3">Jenis Bantuan</th>
                    <th class="px-6 py-3">Tahap</th>
                    <th class="px-6 py-3">Nilai Bantuan (Rp)</th>
                    <th class="px-6 py-3">Tanggal Pengajuan</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pencairan_bantuan as $i => $row)
                <tr class="border-b hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">{{ $i + 1 }}</td>
                    
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $row->pengajuan_kube?->kube?->nama_kube ?? '-' }}
                    </td>
                    
                    <td class="px-6 py-4">
                        {{ $row->pengajuan_kube?->jenisBantuan->jenis_bantuan ?? '-' }}
                    </td>
                    
                    <td class="px-6 py-4 font-mono text-gray-700">
                        {{ $row->tahap ?? '-' }}
                    </td>
                    
                    <td class="px-6 py-4 font-mono text-gray-900">
                        {{ $row->pengajuan_kube?->jumlah_bantuan ? number_format($row->pengajuan_kube->jumlah_bantuan, 0, ',', '.') : '-' }}
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $row->pengajuan_kube?->tanggal_pengajuan ? \Carbon\Carbon::parse($row->pengajuan_kube->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') : '-' }}
                    </td>
                    
                    <td class="px-6 py-4">
                        @if ($row->status_pencairan == 'menunggu')
                            <span class="px-2 py-1 rounded text-white bg-yellow-500">Menunggu</span>
                        @elseif ($row->status_pencairan == 'disetujui')
                            <span class="px-2 py-1 rounded text-white bg-blue-500">Disetujui</span>
                        @elseif ($row->status_pencairan == 'ditolak')
                            <span class="px-2 py-1 rounded text-white bg-red-500">Ditolak</span>
                        @elseif ($row->status_pencairan == 'cair')
                            <span class="px-2 py-1 rounded text-white bg-emerald-600">Cair</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">
                        Belum ada data pencairan bantuan.
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
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->pengajuan_kube?->jenisBantuan->jenis_bantuan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->tahap ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->pengajuan_kube?->jumlah_bantuan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->pengajuan_kube?->tanggal_pengajuan ? \Carbon\Carbon::parse($row->pengajuan_kube->tanggal_pengajuan)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                @if ($row->status_pencairan == 'menunggu')
                                    <span class="bg-yellow-200 px-2 py-1 text-xs rounded-md text-yellow-800">Menunggu</span>
                                @elseif ($row->status_pencairan == 'ditolak')
                                    <span class="bg-red-200 px-2 py-1 text-xs rounded-md text-red-800">Ditolak</span>
                                @elseif ($row->status_pencairan == 'disetujui')
                                    <span class="bg-blue-200 px-2 py-1 text-xs rounded-md text-blue-800">Disetujui</span>
                                @elseif ($row->status_pencairan == 'cair')
                                    <span class="bg-emerald-200 px-2 py-1 text-xs rounded-md text-emerald-800">Cair</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if ($row->status_pencairan == 'menunggu')
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.pencairan_bantuan.accept', $row->id_pencairan) }}"
                                            onclick="return confirm('Apakah anda yakin menyetujui pencairan {{ $row->pengajuan_kube?->kube?->nama_kube ?? 'ini' }}?')"
                                            class="text-white bg-blue-600 hover:bg-blue-700 text-sm px-3 py-1 rounded-md">
                                            Setujui
                                        </a>
                                        <a href="{{ route('admin.pencairan_bantuan.reject', $row->id_pencairan) }}"
                                            onclick="return confirm('Apakah anda yakin menolak pencairan {{ $row->pengajuan_kube?->kube?->nama_kube ?? 'ini' }}?')"
                                            class="text-white bg-red-600 hover:bg-red-700 text-sm px-3 py-1 rounded-md">
                                            Tolak
                                        </a>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            {{-- <td class="px-6 py-4">{{ $row->deskripsi }}</td>
                <td class="px-6 py-4">{{ $row->nama_kategori ?? '-' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-white {{ $row->status == 'Aktif' ? 'bg-green-500' : 'bg-red-500' }}">
                        {{ $row->status }}
                    </span>
                </td> --}}
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

    {{-- ================= MODAL TAMBAH ================= --}}
    <div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/10 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col">

            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Daftar Pengajuan Bantuan</h3>
                <button onclick="toggleModal('modal-tambah')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-6 overflow-x-auto">
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
                                    <form action="{{ route('admin.pencairan_bantuan.tambah', $row->id_pengajuan_kube) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah anda yakin ingin membuat pencairan untuk {{ $row->kube->nama_kube }}?')">
                                        @csrf
                                        @method('post')
                                        <button type="submit"
                                            class="px-3 py-1 rounded-md text-sm bg-green-500 text-white hover:bg-green-700 transition duration-200 ease-in-out">
                                            Buat Pencairan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">
                                    Belum ada data pengajuan KUBE.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end">
                <button onclick="toggleModal('modal-tambah')"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function toggleModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.toggle('hidden');
                }
            }
        </script>
    @endpush
@stop
