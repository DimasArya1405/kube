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
        
        <button data-modal-target="modal-tambah" data-modal-toggle="modal-tambah" class="block text-white bg-blue-600 hover:bg-blue-700 rounded-lg text-sm px-5 py-2.5">
            Tambah Kategori
        </button>
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
                            <a href="{{ route('kategorikube.edit', $item->id_kategori) }}"
                               class="px-3 py-1 bg-blue-500 text-white rounded-lg text-xs">
                                Edit
                            </a>

                            <!-- HAPUS -->
                            <form action="{{ route('kategorikube.destroy', $item->id_kategori) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Yakin hapus data?')"
                                    class="px-3 py-1 bg-red-500 text-white rounded-lg text-xs">
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
                        <input type="text" name="nama_kategori"placeholder="Masukkan nama kategori"
                        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-3">
                        <label class="text-sm">Deskripsi</label>
                        <textarea name="deskripsi"placeholder="Masukkan deskripsi kategori"
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