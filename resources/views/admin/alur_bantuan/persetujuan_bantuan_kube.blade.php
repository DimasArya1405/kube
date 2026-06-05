@extends('admin.layout')

@section('title', 'Data Pengajuan Bantuan - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Pengajuan Bantuan KUBE</span>
@stop

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Pengajuan Bantuan KUBE</h2>
        <p class="text-gray-500 mt-1">Kelola data pengajuan bantuan KUBE.</p>
    </div>
    <div class="flex justify-end gap-2">

        <!-- <a href="{{ route('admin.alur_bantuan.jenis_bantuan.index') }}"
            class="text-white bg-green-600 hover:bg-green-700 px-5 py-2.5 rounded-lg">
            Olah Data Jenis Bantuan
        </a> -->

        <!-- <button data-modal-target="modal-tambah" data-modal-toggle="modal-tambah"
            class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-lg">
            Tambah Cluster
        </button> -->
    </div>
</div>

{{-- @if (session('success'))
<div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div class="mb-4 p-4 rounded-lg bg-red-100 text-red-700">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif --}}

<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama Kube</th>
                    <th class="px-6 py-3">Jenis Bantuan</th>
                    <th class="px-6 py-3">Jumlah Bantuan</th>
                    <th class="px-6 py-3">Status Pengajuan</th>
                    <th class="px-6 py-3">Disetujui/Ditolak Oleh</th>
                    <th class="px-6 py-3">Tanggal Pengajuan</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengajuan_kube as $i => $row)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $i + 1 }}</td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $row->kube->nama_kube ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $row->jenisBantuan->jenisBantuan->jenis_bantuan ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ number_format($row->jumlah_bantuan, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4">
                        @if ($row->status_pengajuan == 'diajukan')
                        <span class="px-2 py-1 rounded text-white bg-yellow-500">Diajukan</span>
                        @elseif ($row->status_pengajuan == 'menunggu')
                        <span class="px-2 py-1 rounded text-white bg-blue-500">Menunggu</span>
                        @elseif ($row->status_pengajuan == 'disetujui')
                        <span class="px-2 py-1 rounded text-white bg-green-500">Disetujui</span>
                        @elseif ($row->status_pengajuan == 'ditolak')
                        <span class="px-2 py-1 rounded text-white bg-red-500">Ditolak</span>
                        @elseif ($row->status_pengajuan == 'cair')
                        <span class="px-2 py-1 rounded text-white bg-emerald-600">Cair</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        {{ $row->penyetuju->nama ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($row->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y') }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex gap-2 items-center flex-wrap">
                            <a href="{{ route('kadis.persetujuan_bantuan_kube.detail', $row->id_pengajuan_kube) }}"
                                class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Detail
                            </a>

                            @if (in_array($row->status_pengajuan, ['disetujui', 'cair']))
                            <a href="{{ route('kadis.persetujuan_bantuan_kube.unduh_berita_acara', $row->id_pengajuan_kube) }}"
                                class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-1">
                                <i class="bi bi-download"></i>
                                Unduh Berita Acara
                            </a>
                            @elseif (!in_array($row->status_pengajuan, ['diajukan', 'menunggu']))
                            <span class="text-gray-500 italic">Sudah diproses</span>
                            @endif
                        </div>
                    </td>
                    </td>
                </tr>

                {{-- Modal Setujui --}}
                <div id="modal-setujui-{{ $row->id_pengajuan_kube }}"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Setujui Pengajuan</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Apakah kamu yakin ingin menyetujui pengajuan bantuan untuk
                            <span class="font-semibold">{{ $row->kube->nama_kube ?? '-' }}</span>?
                        </p>

                        <form action="{{ route('admin.persetujuan_bantuan_kube.setujui', $row->id_pengajuan_kube) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="flex justify-end gap-2">
                                <button type="button"
                                    onclick="document.getElementById('modal-setujui-{{ $row->id_pengajuan_kube }}').classList.add('hidden')"
                                    class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                                    Batal
                                </button>

                                <button type="submit"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Ya, Setujui
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal Tolak --}}
                <div id="modal-tolak-{{ $row->id_pengajuan_kube }}"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Tolak Pengajuan</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Apakah kamu yakin ingin menolat pengajuan bantuan untuk
                            <span class="font-semibold">{{ $row->kube->nama_kube ?? '-' }}</span>?
                        </p>

                        <form action="{{ route('admin.persetujuan_bantuan_kube.tolak', $row->id_pengajuan_kube) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="flex justify-end gap-2">
                                <button type="button"
                                    onclick="document.getElementById('modal-tolak-{{ $row->id_pengajuan_kube }}').classList.add('hidden')"
                                    class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                                    Batal
                                </button>

                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    Tolak
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Belum ada data pengajuan KUBE.
                    </td>
                </tr>
                @endforelse
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