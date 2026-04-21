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
                    <th class="px-2 py-2">No.</th>
                    <th class="px-6 py-3">Nama Kategori</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <!-- <th class="px-6 py-3">Status</th> -->
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $index => $item)
                <tr class="border-b">
                    <td class="px-6 py-4">
                        {{ $index + 1 }}
                    </td>
                    
                    <td class="px-6 py-4 font-medium">
                        {{ $item->nama_kategori }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->deskripsi }}
                    </td>

                    <!-- <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full 
                                {{ $item->status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $item->status }}
                        </span>
                    </td> -->

                    <!-- Aksi -->
                    <td class="px-4 py-3 flex gap-2">            
                        <!-- EDIT -->
                        <button data-modal-target="modal-edit-{{ $item->id_kategori }}"
                        data-modal-toggle="modal-edit-{{ $item->id_kategori }}"
                        class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                        </button>

                        <!-- HAPUS -->
                        <a href="{{ route('kategorikube.destroy', $item->id_kategori) }}" method="POST" style="display:inline;">
                            @csrf
                            <button onclick="return confirm('Yakin mau hapus?')"
                            class="text-red-500 hover:text-red-700" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                            </button>
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

                <!-- <div class="mb-3">
                    <label class="text-sm">Status</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>
                    </select>
                </div> -->

                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">
                    Simpan Data
                </button>
            </form>
    </div>
    </div>
</div>
@stop

<!-- MODAL EDIT -->
@foreach($data as $index => $item)
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

                <!-- <div class="mb-3">
                    <label class="text-sm">Status</label>
                    <select name="status" class="w-full border rounded p-2">
                        <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ $item->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div> -->

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded">
                    Update Data
                </button>
            </form>

        </div>
    </div>
</div>
@endforeach  