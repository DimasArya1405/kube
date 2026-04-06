@extends('admin.layout')

@section('title', 'Data Perkembangan Usaha - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Perkembangan Usaha</span>
@stop

@section('content')

<!-- HEADER -->
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Perkembangan Usaha</h2>
        <p class="text-gray-500 mt-1">
            Kelola data perkembangan usaha KUBE.
        </p>
    </div>

    <button
        data-modal-target="modal-tambah"
        data-modal-toggle="modal-tambah"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
        + Tambah Data
    </button>
</div>

<!-- TABLE -->
<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">

            <!-- HEADER TABLE -->
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

            <!-- BODY TABLE -->
            <tbody>
                @forelse ($data as $index => $item)
                <tr class="border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $loop->iteration }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $item->laporan->cluster->nama_kube ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->laporan->periode_bulan }}/{{ $item->laporan->periode_tahun }}
                    </td>

                    <td class="px-6 py-4">
                        Rp {{ number_format($item->laporan->omset_pendapatan ?? 0) }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->jumlah_tenaga_kerja }} orang
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $item->perkembangan_usaha == 'Meningkat' ? 'bg-green-100 text-green-700' :
                               ($item->perkembangan_usaha == 'Menurun' ? 'bg-red-100 text-red-700' :
                               'bg-yellow-100 text-yellow-700') }}">
                            {{ $item->perkembangan_usaha }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $item->status_hasil == 'Tercapai'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}">
                            {{ $item->status_hasil }}
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-3">

                            <!-- DELETE -->
                            <form
                                action="{{ route('perkembangan.delete', $item->id_perkembangan) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-500 hover:text-red-700">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-500">
                        Belum ada data perkembangan usaha
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="modal-tambah" tabindex="-1" class="hidden fixed inset-0 z-50 bg-black/50 flex justify-center items-center">

    <div class="bg-white rounded-lg shadow p-6 w-full max-w-2xl">

        <h3 class="text-lg font-semibold mb-4">Tambah Data Perkembangan Usaha</h3>

        <form action="{{ route('perkembangan.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-2 gap-4">

            <!-- LAPORAN -->
            <div>
                <label class="text-sm">Pilih Laporan</label>
                <select name="id_laporan" id="laporan" class="w-full border p-2 rounded">
                    <option value="">Pilih</option>
                    @foreach($laporan as $l)
                        <option 
                            value="{{ $l->id_laporan }}"
                            data-kube="{{ $l->cluster->nama_kube }}"
                            data-bulan="{{ $l->periode_bulan }}"
                            data-tahun="{{ $l->periode_tahun }}"
                            data-omset="{{ $l->omset_pendapatan }}"
                        >
                            {{ $l->cluster->nama_kube }} - {{ $l->periode_bulan }}/{{ $l->periode_tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- TENAGA KERJA -->
            <div>
                <label class="text-sm">Tenaga Kerja</label>
                <input type="number" name="jumlah_tenaga_kerja" class="w-full border p-2 rounded">
            </div>

            <!-- AUTO -->
            <div>
                <label class="text-sm">Nama KUBE</label>
                <input type="text" id="nama_kube" readonly class="w-full border p-2 rounded bg-gray-100">
            </div>

            <div>
                <label class="text-sm">Omset</label>
                <input type="text" id="omset" readonly class="w-full border p-2 rounded bg-gray-100">
            </div>

            <div>
                <label class="text-sm">Periode</label>
                <input type="text" id="periode" readonly class="w-full border p-2 rounded bg-gray-100">
            </div>

            <!-- PERKEMBANGAN -->
            <div>
                <label class="text-sm">Perkembangan</label>
                <select name="perkembangan_usaha" class="w-full border p-2 rounded">
                    <option>Meningkat</option>
                    <option>Tetap</option>
                    <option>Menurun</option>
                </select>
            </div>

            <!-- STATUS -->
            <div>
                <label class="text-sm">Status</label>
                <select name="status_hasil" class="w-full border p-2 rounded">
                    <option>Tercapai</option>
                    <option>Belum Tercapai</option>
                </select>
            </div>

            <!-- EVALUASI -->
            <div>
                <label class="text-sm">Hasil Evaluasi</label>
                <textarea name="hasil_evaluasi" class="w-full border p-2 rounded"></textarea>
            </div>

            <!-- REKOMENDASI -->
            <div>
                <label class="text-sm">Rekomendasi</label>
                <textarea name="rekomendasi" class="w-full border p-2 rounded"></textarea>
            </div>

        </div>

        <div class="flex justify-end mt-4 gap-2">
            <button type="button" data-modal-toggle="modal-tambah"
                class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
            <button class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
        </div>

        </form>
    </div>
</div>

<!-- SCRIPT AUTO ISI -->
<script>
document.getElementById('laporan').addEventListener('change', function() {
    let selected = this.options[this.selectedIndex];

    document.getElementById('nama_kube').value = selected.getAttribute('data-kube');
    document.getElementById('omset').value = "Rp " + Number(selected.getAttribute('data-omset')).toLocaleString();
    document.getElementById('periode').value =
        selected.getAttribute('data-bulan') + "/" + selected.getAttribute('data-tahun');
});
</script>

@endsection