@extends('admin.layout')

@section('title', 'Data Alur Bantuan - Jenis Bantuan')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Jenis Bantuan</span>
@stop

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Jenis Bantuan</h2>
        <p class="text-gray-500 mt-1">Kelola data jenis bantuan KUBE.</p>
    </div>
    <div class="flex justify-end gap-2">
        <button data-modal-target="modal-tambah" data-modal-toggle="modal-tambah"
            class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg">
            Tambah Data
        </button>
    </div>
</div>

<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Jenis Bantuan</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jenis_bantuan as $i => $row)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $i+1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $row->jenis_bantuan }}</td>
                    <td>
                        {{-- aksi --}}
                        <div class="flex gap-2 items-center">
                            <a href=""
                                class="text-white bg-yellow-600 hover:bg-yellow-700 px-5 py-2.5 rounded-lg">
                                Edit
                            </a>
                            <a href=""
                                class="text-white bg-red-600 hover:bg-red-700 px-5 py-2.5 rounded-lg">
                                Hapus
                            </a>
                        </div>
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
        <h3 class="text-lg font-semibold mb-4">Tambah Jenis Bantuan</h3>

        <form action="{{route('admin.alur_bantuan.jenis_bantuan.tambah')}}" method="POST">
            @csrf
            @method('post')
            <input type="text" name="jenis_bantuan" placeholder="Jenis Bantuan"
                class="w-full mb-2 border p-2 rounded" required>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
                Simpan
            </button>
        </form>
    </div>
</div>

@stop