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
                class="text-white bg-green-600 hover:bg-green-700 px-5 py-2.5 rounded-lg">
                Olah Data Jenis Bantuan
            </a>
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
                                {{ $row->jenis_bantuan?->jenis_bantuan ?? '-' }}    
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->tahap ?? '-' }}    
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->nilai_bantuan ?? '-' }}    
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->tanggal_pengajuan ?? '-' }}    
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <span class="px-2 py-1 text-sm bg-green-500 text-white">
                                {{ $row->status_pencairan ?? '-' }}    
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2">
                                <a href=""
                                    class="text-white bg-blue-600 hover:bg-blue-700 text-sm px-3 py-1 rounded-md">
                                    Setujui
                                </a>
                                <a href=""
                                    class="text-white bg-red-600 hover:bg-red-700 text-sm px-3 py-1 rounded-md">
                                    Tolak
                                </a>
                                </div>
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
    {{-- <div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Tambah Cluster</h3>

        <form action="/cluster_usaha" method="POST">
            @csrf

            <input type="text" name="nama_cluster" placeholder="Nama Cluster"
                class="w-full mb-2 border p-2 rounded" required>

            <textarea name="deskripsi" placeholder="Deskripsi"
                class="w-full mb-2 border p-2 rounded"></textarea>

            <select name="id_kategori" class="w-full mb-2 border p-2 rounded" required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
@endforeach
</select>

<select name="status" class="w-full mb-3 border p-2 rounded">
    <option value="Aktif">Aktif</option>
    <option value="Tidak Aktif">Tidak Aktif</option>
</select>

<button class="bg-blue-600 text-white px-4 py-2 rounded w-full">
    Simpan
</button>
</form>
</div>
</div> --}}

@stop
