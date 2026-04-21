@extends('admin.layout')

@section('title', 'Data Cluster Usaha - KUBE')

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

    <div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-sm text-gray-700 bg-gray-200">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Nama Kube</th>
                        <th class="px-6 py-3">Jenis Bantuan</th>
                        <th class="px-6 py-3">Tahap</th>
                        <th class="px-6 py-3">Nilai Bantuan</th>
                        <th class="px-6 py-3">Tanggal Pengajuan</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pencairan_bantuan as $i => $row)
                        <tr class="border-b">
                            <td class="px-6 py-4">{{ $i + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->pengajuan_kube?->kube?->first()?->nama_kube ?? '-' }}
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
                    @endforeach
                </tbody>
            </table>
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
