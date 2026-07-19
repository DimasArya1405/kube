@extends('admin.layout')

@section('title', 'Detail Pencairan Bantuan - KUBE')

@section('breadcrumb')
Dashboard /
<a href="{{ route('admin.pencairan_bantuan.index') }}" class="text-blue-600 hover:underline">
    Pencairan Bantuan
</a> /
<span class="text-gray-800">Detail</span>
@stop

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Detail Pencairan Bantuan</h2>
        <p class="text-gray-500 mt-1">Daftar pencairan bantuan milik KUBE yang dipilih.</p>
    </div>

    <a href="{{ route('admin.pencairan_bantuan.index', ['tahun' => request('tahun'), 'status' => request('status')]) }}"
        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden mb-6">
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi KUBE</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nama KUBE</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $kube->nama_kube ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Desa / Kelurahan</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $kube->desa->nama_desa_kelurahan ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Cluster Usaha</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $kube->clusterUsaha->nama_cluster ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Total Pencairan</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pencairan_bantuan->count() }} pencairan
                </div>
            </div>
        </div>
    </div>
</div>

@if(request('tahun') || request('status'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan data filter:
        @if(request('tahun')) Tahun <span class="font-semibold text-gray-800">{{ request('tahun') }}</span> @endif
        @if(request('status')) Status <span class="font-semibold text-gray-800">{{ ucfirst(request('status')) }}</span> @endif
    </div>
@endif

<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Jenis Bantuan</th>
                    <th class="px-6 py-3">Tahap</th>
                    <th class="px-6 py-3">Nilai Bantuan (Rp)</th>
                    <th class="px-6 py-3">Tanggal Pengajuan</th>
                    <th class="px-6 py-3">Tanggal Cair</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pencairan_bantuan as $i => $row)
                <tr class="border-b hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">{{ $i + 1 }}</td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $row->pengajuan_kube?->jenisBantuan->jenis_bantuan ?? '-' }}
                    </td>

                    <td class="px-6 py-4 font-mono text-gray-700">
                        Tahap {{ $row->tahap ?? '-' }}
                    </td>

                    <td class="px-6 py-4 font-mono text-gray-900">
                        {{ $row->pengajuan_kube?->jumlah_bantuan ? number_format($row->pengajuan_kube->jumlah_bantuan, 0, ',', '.') : '-' }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $row->pengajuan_kube?->tanggal_pengajuan ? \Carbon\Carbon::parse($row->pengajuan_kube->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') : '-' }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $row->tanggal_cair ? \Carbon\Carbon::parse($row->tanggal_cair)->locale('id')->translatedFormat('d F Y') : '-' }}
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
                        KUBE ini belum memiliki data pencairan bantuan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
