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
        <p class="text-gray-500 mt-1">Informasi lengkap data pengajuan bantuan KUBE.</p>
    </div>

    <a href="{{ route('admin.persetujuan_bantuan_kube.index') }}"
        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        Kembali
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border overflow-hidden">
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mt-6 mb-3">
                    Informasi Pengajuan Bantuan KUBE
                </h3>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nama KUBE</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->kube->nama_kube ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Pengaju</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->user->nama ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Jenis Bantuan</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->jenisBantuan->jenis_bantuan ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Jumlah Bantuan</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    Rp {{ number_format($pengajuan->jumlah_bantuan, 0, ',', '.') }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Tujuan Pengajuan</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->tujuan_pengajuan ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal Pengajuan</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Tanggal Disetujui</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->tanggal_disetujui 
                        ? \Carbon\Carbon::parse($pengajuan->tanggal_disetujui)->locale('id')->translatedFormat('d F Y') 
                        : '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Disetujui / Ditolak Oleh</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->penyetuju->nama ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Status Pengajuan</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    @if ($pengajuan->status_pengajuan == 'diajukan')
                    <span class="px-2 py-1 rounded text-white bg-yellow-500">Diajukan</span>
                    @elseif ($pengajuan->status_pengajuan == 'menunggu')
                    <span class="px-2 py-1 rounded text-white bg-blue-500">Menunggu</span>
                    @elseif ($pengajuan->status_pengajuan == 'disetujui')
                    <span class="px-2 py-1 rounded text-white bg-green-500">Disetujui</span>
                    @elseif ($pengajuan->status_pengajuan == 'ditolak')
                    <span class="px-2 py-1 rounded text-white bg-red-500">Ditolak</span>
                    @elseif ($pengajuan->status_pengajuan == 'cair')
                    <span class="px-2 py-1 rounded text-white bg-emerald-600">Cair</span>
                    @endif
                </div>
            </div>

            <!-- <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Status Penerima</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    @if ($pengajuan->status_penerima == 'menunggu')
                    <span class="px-2 py-1 rounded text-white bg-blue-500">Menunggu</span>
                    @elseif ($pengajuan->status_penerima == 'diterima')
                    <span class="px-2 py-1 rounded text-white bg-green-500">Diterima</span>
                    @elseif ($pengajuan->status_penerima == 'ditolak')
                    <span class="px-2 py-1 rounded text-white bg-red-500">Ditolak</span>
                    @endif
                </div>
            </div> -->

            @if ($pengajuan->status_pengajuan == 'ditolak')
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Keterangan</label>
                <div class="border rounded px-4 py-2 bg-gray-50 min-h-[80px]">
                    {{ $pengajuan->keterangan ?? '-' }}
                </div>
            </div>
            @endif

            <div class="md:col-span-2">
                <h3 class="text-lg font-bold text-gray-800 mt-6 mb-3">
                    Informasi KUBE
                </h3>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Nama KUBE</label>
                <div class="border rounded px-4 py-2 bg-gray-50">
                    {{ $pengajuan->kube->nama_kube ?? '-' }}
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

            {{-- 🔥 INI YANG KAMU MINTA --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-600 mb-1">Keterangan KUBE</label>
                <div class="border rounded px-4 py-2 bg-gray-50 min-h-[80px]">
                    {{ $pengajuan->kube->keterangan ?? '-' }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop