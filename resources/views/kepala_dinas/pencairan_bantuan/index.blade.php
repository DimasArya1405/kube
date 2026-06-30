@extends('kepala_dinas.layout')

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
        </div>
    </div>

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
                        <th class="px-6 py-3">Total Nilai Bantuan</th>
                        <th class="px-6 py-3">Pencairan Terakhir</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kube_pencairan as $i => $row)
                        <tr class="border-b">
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
                                <a href="{{ route('kadis.pencairan_bantuan.detail', $row->id_kube) }}"
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
