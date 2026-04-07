@extends('admin.layout')

@section('title', 'Perkembangan Usaha - KUBE')

@section('content')

<div class="mb-6">

    <!-- JUDUL -->
    <h2 class="text-3xl font-bold text-gray-800">Data Perkembangan Usaha</h2>
    <h3 class="text-lg font-semibold mt-2 border-b-2 inline-block">
        Riwayat Data Perkembangan Usaha
    </h3>

    <!-- CARD + BUTTON -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mt-6 gap-4">

        <!-- CARD -->
        <div class="flex gap-4">

            <div class="bg-white px-6 py-4 rounded-xl shadow text-center w-40">
                <h3 class="text-3xl font-bold text-indigo-600">
                    {{ $data->where('status_hasil','Tercapai')->count() }}
                </h3>
                <p class="text-xs text-gray-500 mt-1">Status Tercapai</p>
            </div>

            <div class="bg-white px-6 py-4 rounded-xl shadow text-center w-40">
                <h3 class="text-3xl font-bold text-green-600">
                    {{ $data->where('perkembangan_usaha','Meningkat')->count() }}
                </h3>
                <p class="text-xs text-gray-500 mt-1">Perkembangan Meningkat</p>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex gap-3">

            <a href="#" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm shadow">
                Ekspor PDF
            </a>

            <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                Ekspor Excel
            </a>

            <button data-modal-target="modal-tambah"
                data-modal-toggle="modal-tambah"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                Tambah Data
            </button>

        </div>

    </div>

    <!-- FILTER + SEARCH -->
    <div class="flex flex-col md:flex-row md:justify-between mt-4 gap-3">

        <div class="flex gap-2">
            <select class="border rounded px-3 py-2 text-sm bg-white shadow-sm">
                <option>Kube</option>
            </select>

            <select class="border rounded px-3 py-2 text-sm bg-white shadow-sm">
                <option>Status</option>
            </select>

            <select class="border rounded px-3 py-2 text-sm bg-white shadow-sm">
                <option>Perkembangan</option>
            </select>
        </div>

        <input type="text" placeholder="Cari"
            class="border rounded-lg px-4 py-2 text-sm shadow-sm w-48" />

    </div>

</div>

<!-- TABLE -->
<div class="bg-white rounded-lg shadow border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama KUBE</th>
                    <th class="px-6 py-3">Periode</th>
                    <th class="px-6 py-3">Omset</th>
                    <th class="px-6 py-3">Tenaga Kerja</th>
                    <th class="px-6 py-3">Perkembangan</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>

                    <td class="px-6 py-4 font-semibold text-gray-800">
                        {{ $item->laporan->cluster->nama_kube ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->laporan->periode_bulan ?? '-' }}/{{ $item->laporan->periode_tahun ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        Rp {{ number_format($item->laporan->omset_pendapatan ?? 0, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->jumlah_tenaga_kerja ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs
                            @if($item->perkembangan_usaha == 'Meningkat') bg-green-100 text-green-700
                            @elseif($item->perkembangan_usaha == 'Menurun') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $item->perkembangan_usaha }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs
                            {{ $item->status_hasil == 'Tercapai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $item->status_hasil }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('perkembangan.delete', $item->id_perkembangan) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin hapus?')" class="text-red-500">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-500">
                        Belum ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- BUTTON KANAN BAWAH -->
<div class="flex justify-end mt-4">
    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm shadow">
        Grafik Perkembangan
    </button>
</div>

<!-- MODAL -->
<div id="modal-tambah" tabindex="-1"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">

    <div class="bg-white w-full max-w-lg rounded-lg p-6 relative">

        <!-- tombol X -->
        <button type="button" data-modal-hide="modal-tambah"
            class="absolute top-2 right-3 text-gray-500 text-xl">
            ✕
        </button>

        <h3 class="text-lg font-bold mb-4">Tambah Data</h3>

        <form method="POST" action="{{ route('perkembangan.store') }}">
            @csrf

            <div class="mb-3">
                <label class="text-sm">Pilih KUBE & Periode</label>
                <select name="id_laporan" class="w-full border rounded p-2" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($laporan as $lap)
                        <option value="{{ $lap->id_laporan }}">
                            {{ $lap->cluster->nama_kube }} - 
                            {{ $lap->periode_bulan }}/{{ $lap->periode_tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="text-sm">Jumlah Tenaga Kerja</label>
                <input type="number" name="jumlah_tenaga_kerja" class="w-full border rounded p-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">Perkembangan</label>
                <select name="perkembangan_usaha" class="w-full border rounded p-2">
                    <option value="Meningkat">Meningkat</option>
                    <option value="Tetap">Tetap</option>
                    <option value="Menurun">Menurun</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="text-sm">Status</label>
                <select name="status_hasil" class="w-full border rounded p-2">
                    <option value="Tercapai">Tercapai</option>
                    <option value="Belum Tercapai">Belum Tercapai</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="text-sm">Evaluasi</label>
                <textarea name="hasil_evaluasi" class="w-full border rounded p-2"></textarea>
            </div>

            <div class="mb-3">
                <label class="text-sm">Rekomendasi</label>
                <textarea name="rekomendasi" class="w-full border rounded p-2"></textarea>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" data-modal-hide="modal-tambah"
                    class="px-4 py-2 border rounded">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

@endsection