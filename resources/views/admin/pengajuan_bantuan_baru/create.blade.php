@extends('admin.layout')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Pengajuan Bantuan Baru</span>
@stop

@section('title', 'Tambah Pengajuan Bantuan Baru')

@section('content')
<div class="bg-white p-6 rounded-xl shadow">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold mb-2">Tambah Pengajuan Bantuan Baru</h2>
            <p class="text-gray-500">Setiap jenis bantuan yang ditambahkan akan dibuat sebagai ID pengajuan berbeda.</p>
        </div>
        <a href="{{ route('admin.pengajuan_bantuan_baru.index') }}"
            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            Kembali
        </a>
    </div>

    <form id="form-pengajuan" action="{{ route('admin.pengajuan_bantuan_baru.store') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label class="block mb-1">Nama KUBE</label>
            <select name="id_kube" class="w-full border p-2 rounded" required>
                <option value="">Pilih</option>
                @foreach($kube as $k)
                    <option value="{{ $k->id_kube }}" {{ old('id_kube') == $k->id_kube ? 'selected' : '' }}>
                        {{ $k->nama_kube }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Jenis Bantuan</label>
            <select id="jenis_bantuan" class="w-full border p-2 rounded">
                <option value="">Pilih</option>
                @foreach($jenisBantuan as $j)
                    <option value="{{ $j->id_jenis_bantuan }}">{{ $j->jenis_bantuan }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Jumlah Bantuan</label>
            <div class="flex gap-2 items-center">
                <input type="number" id="jumlah_bantuan" min="1" class="w-full border p-2 rounded">
                <span id="satuan" class="text-gray-600 text-sm">-</span>
            </div>
        </div>

        <div class="mb-3">
            <label class="block mb-1">Keterangan / Nama Item</label>
            <textarea id="keterangan" class="w-full border p-2 rounded"></textarea>
        </div>

        <button type="button" id="btn-tambah"
            class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
            Tambah ke Tabel
        </button>

        <table class="w-full border text-sm mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Jenis</th>
                    <th class="p-2">Nama Item</th>
                    <th class="p-2">Jumlah</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>

        <input type="hidden" name="items" id="items-json">

        <div class="flex justify-end">
            <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Simpan Pengajuan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    let data = [];

    const jenis = document.getElementById('jenis_bantuan');
    const jumlah = document.getElementById('jumlah_bantuan');
    const ket = document.getElementById('keterangan');
    const table = document.getElementById('table-body');
    const satuan = document.getElementById('satuan');

    function updateSatuan() {
        const text = jenis.options[jenis.selectedIndex]?.text;

        if (text === 'Modal Usaha') satuan.innerText = 'Rp';
        else if (text === 'Alat') satuan.innerText = 'Unit';
        else if (text === 'Pelatihan') satuan.innerText = 'Peserta';
        else satuan.innerText = '-';
    }

    function formatJumlah(jenisText, jumlahValue) {
        let angka = Number(jumlahValue).toLocaleString('id-ID');

        if (jenisText === 'Modal Usaha') return 'Rp ' + angka;
        if (jenisText === 'Alat') return angka + ' Unit';
        if (jenisText === 'Pelatihan') return angka + ' Peserta';

        return angka;
    }

    function render() {
        table.innerHTML = "";

        if (data.length === 0) {
            table.innerHTML = `<tr><td colspan="4" class="text-center text-gray-400 p-2">Belum ada data</td></tr>`;
            return;
        }

        data.forEach((item, i) => {
            table.innerHTML += `
                <tr>
                    <td class="p-2">${item.jenis}</td>
                    <td class="p-2">${item.nama}</td>
                    <td class="p-2">${formatJumlah(item.jenis, item.jumlah)}</td>
                    <td class="p-2">
                        <button type="button" onclick="hapus(${i})"
                            class="bg-red-500 text-white px-2 py-1 rounded">
                            Hapus
                        </button>
                    </td>
                </tr>
            `;
        });
    }

    window.hapus = function(i) {
        data.splice(i, 1);
        render();
    }

    jenis.addEventListener('change', updateSatuan);

    document.getElementById('btn-tambah').addEventListener('click', function () {
        const jenisText = jenis.options[jenis.selectedIndex]?.text;
        const jumlahVal = jumlah.value;
        const ketVal = ket.value.trim();

        if (!jenis.value || !jumlahVal || Number(jumlahVal) <= 0) {
            alert('Jenis & jumlah wajib diisi');
            return;
        }

        data.push({
            jenis: jenisText,
            nama: ketVal !== '' ? ketVal : jenisText,
            jumlah: jumlahVal,
            id_jenis: jenis.value
        });

        jumlah.value = "";
        ket.value = "";
        render();
    });

    document.getElementById('form-pengajuan').addEventListener('submit', function(e) {
        if (data.length === 0) {
            alert('Tambahkan minimal 1 data');
            e.preventDefault();
            return;
        }

        document.getElementById('items-json').value = JSON.stringify(data);
    });

    render();
    updateSatuan();
});
</script>
@endpush
