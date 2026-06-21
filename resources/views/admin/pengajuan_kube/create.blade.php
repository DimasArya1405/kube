@extends('admin.layout')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Pengajuan KUBE</span>
@stop

@section('title', 'Tambah Pengajuan KUBE')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <h2 class="text-xl font-semibold mb-2">Tambah Pengajuan</h2>
    <p class="text-gray-500 mb-6">Ajukan permohonan bantuan baru</p>

    <form id="form-pengajuan" action="{{ route('pengajuan.store') }}" method="POST">
    @csrf

    {{-- KUBE --}}
    <div class="mb-3">
        <label>Nama KUBE</label>
        <select name="id_kube" class="w-full border p-2 rounded">
            <option value="">Pilih</option>
            @foreach($kube as $k)
                <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
            @endforeach
        </select>
    </div>

    {{-- JENIS --}}
    <div class="mb-3">
        <label>Jenis Bantuan</label>
        <select id="jenis_bantuan" name="id_jenis_bantuan" class="w-full border p-2 rounded">
            <option value="">Pilih</option>
            @foreach($jenisBantuan as $j)
                <option value="{{ $j->id_jenis_bantuan }}">{{ $j->jenis_bantuan }}</option>
            @endforeach
        </select>
    </div>

    {{-- JUMLAH --}}
    <div class="mb-3">
        <label>Jumlah Bantuan</label>
        <div class="flex gap-2 items-center">
            <input type="number" id="jumlah_bantuan" name="jumlah_bantuan" class="w-full border p-2 rounded">
            <span id="satuan" class="text-gray-600 text-sm">-</span>
        </div>
    </div>

    {{-- KETERANGAN --}}
    <div class="mb-3">
        <label>Keterangan</label>
        <textarea id="keterangan" name="keterangan" class="w-full border p-2 rounded"></textarea>
    </div>

    {{-- BUTTON TAMBAH --}}
    <button type="button" id="btn-tambah"
        class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
        Tambah ke Tabel
    </button>

    {{-- TABLE --}}
    <table class="w-full border text-sm mb-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Jenis</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Jumlah</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody id="table-body"></tbody>
    </table>

    <input type="hidden" name="items" id="items-json">

    {{-- SUBMIT --}}
    <div class="flex justify-end">
        <button type="submit"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Ajukan Bantuan
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

    jenis.addEventListener('change', updateSatuan);

    function formatJumlah(jenis, jumlah) {
        let angka = Number(jumlah).toLocaleString('id-ID');

        if (jenis === 'Modal Usaha') return 'Rp ' + angka;
        if (jenis === 'Alat') return angka + ' Unit';
        if (jenis === 'Pelatihan') return angka + ' Peserta';

        return angka;
    }

    function render() {
        table.innerHTML = "";

        if (data.length === 0) {
            table.innerHTML = `<tr><td colspan="4" class="text-center text-gray-400 p-2">Belum ada data</td></tr>`;
            return;
        }

        data.forEach((item, i) => {

            let jumlahFormatted = formatJumlah(item.jenis, item.jumlah);

            table.innerHTML += `
                <tr>
                    <td class="p-2">${item.jenis}</td>
                    <td class="p-2">${item.nama}</td>
                    <td class="p-2">${jumlahFormatted}</td>
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
        data.splice(i,1);
        render();
    }

    document.getElementById('btn-tambah').addEventListener('click', function () {

        const jenisText = jenis.options[jenis.selectedIndex]?.text;
        const jumlahVal = jumlah.value;
        const ketVal = ket.value;

        if (!jenisText || !jumlahVal) {
            alert('Jenis & jumlah wajib diisi');
            return;
        }

        let nama = (jenisText === 'Alat') ? ketVal : jenisText;

        data.push({
            jenis: jenisText,
            nama: nama,
            jumlah: jumlahVal,
            keterangan: ketVal,
            id_jenis: jenis.value
        });

        jumlah.value = "";
        ket.value = "";

        render();
    });

    document.getElementById('form-pengajuan').addEventListener('submit', function(e){

        if (data.length === 0) {
            alert('Tambahkan minimal 1 data');
            e.preventDefault();
            return;
        }

        document.getElementById('items-json').value = JSON.stringify(data);
        console.log("DATA DIKIRIM:", data);
    });

});
</script>
@endpush