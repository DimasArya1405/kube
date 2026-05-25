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
        <a href="{{route('admin.pencairan_bantuan.index')}}"
            class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg">
            Kembali
        </a>
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
                            <a href="{{route('admin.alur_bantuan.jenis_bantuan.hapus',$row->id_jenis_bantuan)}}" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')"
                                class="text-white bg-red-600 hover:bg-red-700 px-5 py-2.5 rounded-lg button-hapus">
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
{{-- ================= MODAL HAPUS ================= --}}
{{-- <div id="modal-hapus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-xl p-8 w-full max-w-sm transform transition-all">
        <div class="flex justify-center mb-4">
            <div class="bg-red-100 p-3 rounded-full">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Konfirmasi Hapus</h3>
        <p class="text-gray-500 text-center mb-6">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>

        <form action="{{route('admin.alur_bantuan.jenis_bantuan.hapus')}}" method="POST">
            @csrf
            @method('DELETE') <div class="flex flex-col gap-3">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2.5 rounded-lg w-full transition-colors">
                    Ya, Hapus Sekarang
                </button>
                <input type="text" id="id_jenis_bantuan" name="id_jenis_bantuan">
                
                <button type="button" data-modal-hide="modal-hapus"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-4 py-2.5 rounded-lg w-full transition-colors">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div> --}}
@stop