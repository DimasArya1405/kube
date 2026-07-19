@extends('admin.layout')

@section('title', 'Detail Pengajuan Bantuan KUBE')

@section('breadcrumb')
Dashboard /
<a href="{{ route('admin.persetujuan_bantuan_kube.index') }}" class="text-blue-600 hover:underline">
    Pengajuan Bantuan KUBE
</a> /
<span class="text-gray-800">Detail</span>
@stop

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Detail Pengajuan Bantuan KUBE</h2>
        <p class="text-gray-500 mt-1">Informasi KUBE dan daftar jenis bantuan yang diajukan.</p>
    </div>

    <div class="flex gap-2">
        @if ($pengajuan_kube->whereIn('status_pengajuan', ['disetujui', 'cair'])->count() > 0)
        <a href="{{ route('admin.persetujuan_bantuan_kube.unduh_berita_acara_semua', $pengajuan->id_kube) }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Unduh BA Semua Disetujui
        </a>
        @endif

        <a href="{{ route('admin.persetujuan_bantuan_kube.index') }}"
            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            Kembali
        </a>
    </div>
</div>

@if (session('success'))
<div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-lg shadow-sm border overflow-hidden mb-6">
    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi KUBE</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nama KUBE</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->kube->nama_kube ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Pengaju</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->users->nama ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Desa / Kelurahan</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->kube->desa->nama_desa_kelurahan ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Cluster Usaha</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->kube->clusterUsaha->nama_cluster ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal Terbentuk</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->kube && $pengajuan->kube->tanggal_terbentuk
                        ? \Carbon\Carbon::parse($pengajuan->kube->tanggal_terbentuk)->locale('id')->translatedFormat('d F Y')
                        : '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Status KUBE</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->kube->status ?? '-' }}
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Keterangan KUBE</label>
                <div class="border rounded px-4 py-2 bg-gray-50 min-h-[80px]">
                    {{ $pengajuan->kube->keterangan ?? '-' }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <div class="p-6 border-b">
        <h3 class="text-lg font-bold text-gray-800">Daftar Jenis Bantuan Diajukan</h3>
        <p class="text-sm text-gray-500 mt-1">Pantau status setiap jenis bantuan dan unduh berita acara jika sudah disetujui.</p>
    </div>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Jenis Bantuan</th>
                    <th class="px-6 py-3">Jumlah Bantuan</th>
                    <th class="px-6 py-3">Tujuan Pengajuan</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Disetujui/Ditolak Oleh</th>
                    <th class="px-6 py-3">Tanggal Pengajuan</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan_kube as $i => $row)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $i + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $row->jenisBantuan->jenis_bantuan ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ number_format($row->jumlah_bantuan, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $row->tujuan_pengajuan ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if ($row->status_pengajuan == 'diajukan')
                        <span class="px-2 py-1 rounded text-white bg-yellow-500">Diajukan</span>
                        @elseif ($row->status_pengajuan == 'menunggu')
                        <span class="px-2 py-1 rounded text-white bg-blue-500">Menunggu</span>
                        @elseif ($row->status_pengajuan == 'disetujui')
                        <span class="px-2 py-1 rounded text-white bg-green-500">Disetujui</span>
                        @elseif ($row->status_pengajuan == 'ditolak')
                        <span class="px-2 py-1 rounded text-white bg-red-500">Ditolak</span>
                        @elseif ($row->status_pengajuan == 'cair')
                        <span class="px-2 py-1 rounded text-white bg-emerald-600">Cair</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        {{ $row->penyetuju->nama ?? '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 items-center flex-wrap">
                            @if(in_array($row->status_pengajuan, ['disetujui','cair']))
                            <a href="{{ route('admin.persetujuan_bantuan_kube.unduh_berita_acara', $row->id_pengajuan_kube) }}"
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Unduh Berita Acara
                            </a>
                            @elseif(in_array($row->status_pengajuan, ['diajukan', 'menunggu']))
                            <span class="text-gray-500">Menunggu keputusan Kepala Dinas</span>
                            @else
                            <span class="text-gray-500 italic">Sudah diproses</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
