@extends('admin.layout')

@section('title', 'Data Galeri - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Galeri KUBE</span>
@stop

@section('content')

<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Galeri</h2>
        <p class="text-gray-500 mt-1">Kelola data galeri kegiatan KUBE.</p>
    </div>
</div>

<!-- SUMMARY CARD -->

<div class="flex gap-4 mb-6">
    <div class="bg-blue-500 text-white rounded-lg px-12 py-5 text-center min-w-[150px] shadow">
        <p class="text-sm font-medium">Total Galeri</p>
        <p class="text-4xl font-bold mt-1">
            {{ $galeri->count() }}
        </p>
    </div>
</div>

<div class="flex justify-between items-center mb-6">

<!-- SEARCH -->
<form action="{{ route('galeri.index') }}" method="GET" class="w-1/2">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Cari galeri..."
        onkeyup="this.form.submit()"
        class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">

</form>

<!-- BUTTON -->
<div class="flex gap-2">

    <button
        data-modal-target="modal-tambah"
        data-modal-toggle="modal-tambah"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg">

        + Tambah Galeri

    </button>

</div>

</div>

<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">

<div class="relative overflow-x-auto">

    <table class="w-full text-sm text-left text-body">

        <thead class="text-sm bg-gray-200 border-b">

            <tr>

                <th class="px-6 py-3">No</th>
                <th class="px-6 py-3">Foto</th>
                <th class="px-6 py-3">Judul</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Deskripsi</th>
                <th class="px-6 py-3">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($galeri as $index => $item)

            <tr class="border-b">

                <td class="px-6 py-4">
                    {{ $index + 1 }}
                </td>

                <td class="px-6 py-4">
                    <img src="{{ asset('images/'.$item->gambar) }}"
                        class="w-20 h-20 object-cover rounded-lg">
                </td>

                <td class="px-6 py-4 font-medium">
                    {{ $item->judul }}
                </td>

                <td class="px-6 py-4">
                    {{ $item->tanggal }}
                </td>

                <td class="px-6 py-4">
                    {{ $item->deskripsi }}
                </td>

                <td class="px-4 py-3 flex items-center gap-3">

                    <!-- EDIT -->
                    <button
                        data-modal-target="modal-edit-{{ $item->id_galeri }}"
                        data-modal-toggle="modal-edit-{{ $item->id_galeri }}"
                        class="text-yellow-500 hover:text-yellow-700"
                        title="Edit">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>

                    </button>

                    <!-- HAPUS -->
                    <a href="{{ route('galeri.delete', $item->id_galeri) }}"
                        onclick="return confirm('Yakin ingin menghapus data ini?')"
                        class="text-red-500 hover:text-red-700"
                        title="Hapus">

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />

                        </svg>

                    </a>

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="6"
                    class="text-center py-6 text-gray-500">

                    Data galeri belum ada

                </td>
            </tr>

            @endforelse

        </tbody>

    </table>

</div>

</div>

<!-- MODAL TAMBAH -->

<div id="modal-tambah"
    class="hidden fixed top-0 left-0 right-0 z-50 justify-center items-center w-full h-full bg-black/50">


<div class="relative p-4 w-full max-w-md">

    <div class="bg-white rounded-lg shadow">

        <div class="flex justify-between p-4 border-b">

            <h3 class="text-lg font-semibold">
                Tambah Galeri
            </h3>

            <button data-modal-toggle="modal-tambah">
                ✖
            </button>

        </div>

        <form method="POST"
            action="{{ route('galeri.store') }}"
            enctype="multipart/form-data"
            class="p-4">

            @csrf

            <div class="mb-3">
                <label class="text-sm">Judul Kegiatan</label>

                <input type="text"
                    name="judul"
                    required
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">Foto</label>

                <input type="file"
                    name="gambar"
                    required
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">Tanggal</label>

                <input type="date"
                    name="tanggal"
                    required
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">Deskripsi</label>

                <textarea
                    name="deskripsi"
                    rows="4"
                    class="w-full border rounded-lg px-4 py-2"></textarea>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded">

                Simpan Data

            </button>

        </form>

    </div>

</div>


</div>

<!-- MODAL EDIT -->

@foreach($galeri as $item)

<div id="modal-edit-{{ $item->id_galeri }}"
    class="hidden fixed top-0 left-0 right-0 z-50 justify-center items-center w-full h-full bg-black/50">


<div class="relative p-4 w-full max-w-md">

    <div class="bg-white rounded-lg shadow">

        <div class="flex justify-between p-4 border-b">

            <h3 class="text-lg font-semibold">
                Edit Galeri
            </h3>

            <button data-modal-toggle="modal-edit-{{ $item->id_galeri }}">
                ✖
            </button>

        </div>

        <form method="POST"
            action="{{ route('galeri.update', $item->id_galeri) }}"
            enctype="multipart/form-data"
            class="p-4">

            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Judul Kegiatan</label>

                <input type="text"
                    name="judul"
                    value="{{ $item->judul }}"
                    class="w-full border rounded-lg px-4 py-2">
            </div>

            <div class="mb-3">

                <img src="{{ asset('images/'.$item->gambar) }}"
                    class="w-24 h-24 object-cover rounded mb-2">

                <label>Ganti Foto</label>

                <input type="file"
                    name="gambar"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <div class="mb-3">

                <label>Tanggal</label>

                <input type="date"
                    name="tanggal"
                    value="{{ $item->tanggal }}"
                    class="w-full border rounded-lg px-4 py-2">

            </div>

            <div class="mb-3">

                <label>Deskripsi</label>

                <textarea
                    name="deskripsi"
                    rows="4"
                    class="w-full border rounded-lg px-4 py-2">{{ $item->deskripsi }}</textarea>

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

@stop
