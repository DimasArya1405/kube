@extends('admin.layout')

@section('title', 'Data Cluster Usaha - KUBE')

@section('breadcrumb')
    Dashboard / <span class="text-gray-800">Cluster Usaha</span>
@stop

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Cluster Usaha</h2>
        <p class="text-gray-500 mt-1">Kelola data cluster usaha KUBE.</p>
    </div>
    
    <button data-modal-target="modal-tambah" data-modal-toggle="modal-tambah"
        class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg">
        Tambah Cluster
    </button>
</div>

<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama Cluster</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $row)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $i+1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $row->nama_cluster }}</td>
                    <td class="px-6 py-4">{{ $row->deskripsi }}</td>
                    <td class="px-6 py-4">{{ $row->nama_kategori ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-white {{ $row->status == 'Aktif' ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ $row->status }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
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
                @foreach($kategori as $k)
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
</div>

@stop