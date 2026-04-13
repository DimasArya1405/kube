@extends('admin.layout')

@section('breadcrumb')
Data Master / Data Kube / <span class="text-gray-800">Detail KUBE</span>
@stop

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6 border-b pb-4">
        <a href="{{ route('kube.index') }}" class="text-gray-600 hover:text-purple-700 transition mr-4 text-2xl">
            <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Detail KUBE {{ $kube->nama_kube }}</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola informasi Kelompok Usaha Bersama, status, dan pembagian pendamping.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 uppercase tracking-wide">Informasi Dasar KUBE</h3>
            <div class="space-y-2 text-gray-700 font-medium">
                <p>Nama KUBE : <span class="uppercase">{{ $kube->nama_kube }}</span></p>
                <p>Kategori : <span>{{ $kube->clusterUsaha->nama_cluster ?? '-' }}</span></p> 
                <p>Cluster : <span>{{ $kube->clusterUsaha->nama_cluster ?? '-' }}</span></p>
                <p>Kecamatan : <span>{{ $kube->desa->nama_desa_kelurahan ?? '-' }}</span></p> 
                <p>Desa/Kelurahan : <span>{{ $kube->desa->nama_desa_kelurahan ?? '-' }}</span></p>
                <p>Status : <span>{{ $kube->status }}</span></p>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 uppercase tracking-wide">Pengelola KUBE</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-700 font-medium mb-1">Pendamping</p>
                    <div class="flex justify-between items-center bg-gray-300 rounded-full px-6 py-2 shadow-inner">
                        <span class="font-bold text-gray-800 mx-auto">{{ $kube->pembagianPendamping->pendamping->nama_pendamping ?? 'Belum ada Pendamping' }}</span>
                    </div>
                </div>
                <div>
                    <p class="text-gray-700 font-medium mb-1">Koordinator</p>
                    <div class="flex justify-between items-center bg-gray-300 rounded-full px-6 py-2 shadow-inner">
                        <span class="font-bold text-gray-800 mx-auto">{{ $kube->pembagianPendamping->pembagianKoordinator->koordinator->nama_koor ?? 'Belum ada Koordinator' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">Daftar Anggota KUBE</h3>
            <div class="flex gap-2">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-search"></i></span>
                    <input type="text" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-gray-50 text-sm" placeholder="Cari nama Anggota...">
                </div>
                <a href="{{ route('anggota_kube.index') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah Anggota
                </a>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">No.</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Nama Anggota</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">NIK</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Jabatan</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">No. HP</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Alamat</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($kube->anggota as $index => $anggota)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $index + 1 }}.</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->nama_anggota }}</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->nik }}</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->jabatan }}</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->no_hp }}</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->alamat }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">Belum ada anggota di KUBE ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection