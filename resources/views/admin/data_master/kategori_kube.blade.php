{{-- TIKA --}}

@extends('admin.layout')

@section('title', 'Data Kategori - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Kategori KUBE</span>
@stop

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Kategori</h2>
        <p class="text-gray-500 mt-1">Kelola data kategori KUBE.</p>
    </div>

</div>
<div class="flex gap-4 mb-6">

    <!-- AKTIF -->
    <div class="bg-orange-400 text-white px-6 py-4 rounded-xl w-60">
        <p class="text-sm">Kategori Aktif</p>
        <h1 class="text-3xl font-bold">
            {{ $data->where('status','Aktif')->count() }}
        </h1>
    </div>

    <!-- NON AKTIF -->
    <div class="bg-green-400 text-white px-6 py-4 rounded-xl w-60">
        <p class="text-sm">Kategori Non-Aktif</p>
        <h1 class="text-3xl font-bold">
            {{ $data->where('status','Nonaktif')->count() }}
        </h1>
    </div>

</div>

<div class="flex justify-between items-center mb-6">

    <!-- SEARCH -->
    <input type="text" placeholder="Cari..."
        class="w-1/2 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

    <!-- BUTTON -->
    <div class="flex gap-2">

        <button class="bg-red-500 text-white px-4 py-2 rounded-lg">
            Ekspor PDF
        </button>

        <button class="bg-green-500 text-white px-4 py-2 rounded-lg">
            Ekspor Excel
        </button>

        <button data-modal-target="modal-tambah" data-modal-toggle="modal-tambah"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg">
            + Tambah Kategori
        </button>

    </div>

</div>
<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-body">
            <thead class="text-sm bg-gray-200 border-b">
                <tr>
                    <th class="px-6 py-3">Nama Kategori</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $item)
                <tr class="border-b">
                    <td class="px-6 py-4 font-medium">
                        {{ $item->nama_kategori }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->deskripsi }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full 
                                {{ $item->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $item->status }}
                        </span>
                    </td>

                    <td class="px-6 py-4 flex gap-2">

                        <!-- EDIT -->
                        <button data-modal-target="modal-edit-{{ $item->id_kategori }}"
                        data-modal-toggle="modal-edit-{{ $item->id_kategori }}"
                        class="bg-blue-500 text-white px-3 py-1 rounded">
                        Edit
                        </button>

                        <!-- HAPUS -->
                        <form action="{{ route('kategorikube.destroy', $item->id_kategori) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            
                            <button onclick="return confirm('Yakin mau hapus?')"
                            class="bg-red-500 text-white px-3 py-1 rounded">
                            Hapus
                        </button>
                    </form>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="4" class="text-center py-6 text-gray-500">
                        Data kategori belum ada
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="modal-tambah" class="hidden fixed top-0 left-0 right-0 z-50 justify-center items-center w-full h-full bg-black/50">
    <div class="relative p-4 w-full max-w-md">
        <div class="bg-white rounded-lg shadow">

            <div class="flex justify-between p-4 border-b">
                <h3 class="text-lg font-semibold">Tambah Kategori</h3>
                <button data-modal-toggle="modal-tambah">✖</button>
            </div>

            <form method="POST" action="{{ route('kategorikube.store') }}" class="p-4">
                @csrf

                <div class="mb-3">
                    <label class="text-sm">Nama Kategori</label>
                    <input type="text" name="nama_kategori" placeholder="Masukkan nama kategori"
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-3">
                    <label class="text-sm">Deskripsi</label>
                    <textarea name="deskripsi" placeholder="Masukkan deskripsi kategori"
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div class="mb-3">
                    <label class="text-sm">Status</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
                    Simpan Data
                </button>
            </form>
    </div>
    </div>
</div>
@stop
<!-- MODAL EDIT -->
@foreach($data as $item)
<div id="modal-edit-{{ $item->id_kategori }}"
    class="hidden fixed top-0 left-0 right-0 z-50 justify-center items-center w-full h-full bg-black/50">

    <div class="relative p-4 w-full max-w-md">
        <div class="bg-white rounded-lg shadow">

            <div class="flex justify-between p-4 border-b">
                <h3 class="text-lg font-semibold">Edit Kategori</h3>
                <button data-modal-toggle="modal-edit-{{ $item->id_kategori }}">✖</button>
            </div>

            <form method="POST" action="{{ route('kategorikube.update', $item->id_kategori) }}" class="p-4">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="text-sm">Nama Kategori</label>
                    <input type="text" name="nama_kategori"
                        value="{{ $item->nama_kategori }}"
                        class="w-full border rounded-lg px-4 py-2">
                </div>

                <div class="mb-3">
                    <label class="text-sm">Deskripsi</label>
                    <textarea name="deskripsi"
                        class="w-full border rounded-lg px-4 py-2">{{ $item->deskripsi }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="text-sm">Status</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ $item->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded">
                    Update Data
                </button>
            </form>

        </div>
    </div>
</div>
@endforeach
        