@extends('admin.layout')

@section('title', 'Prediksi KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Prediksi</span>
@stop

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Prediksi Keberhasilan KUBE</h2>
    <p class="text-gray-500 mt-1">Silakan pilih data KUBE, bulan, dan tahun untuk melakukan prediksi.</p>
</div>

@if(session('error'))
<div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

    {{-- PILIH DATA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

    {{-- Kecamatan --}}
    <div>
        <label class="block mb-2 text-sm font-medium">Kecamatan</label>
        <select id="kecamatan" class="w-full border p-2 rounded">
            <option value="">Pilih Kecamatan</option>
            @foreach($kecamatan as $kec)
                <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
            @endforeach
        </select>
    </div>

    {{-- KUBE --}}
    <div>
        <label class="block mb-2 text-sm font-medium">Nama KUBE</label>
        <select id="kube" class="w-full border p-2 rounded">
            <option value="">Pilih KUBE</option>
        </select>
    </div>

    {{-- Bulan --}}
    <div>
        <label class="block mb-2 text-sm font-medium">Bulan</label>
        <select id="bulan" class="w-full border p-2 rounded">
            <option value="">Pilih Bulan</option>
            @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $bln)
                <option value="{{ $loop->index + 1 }}">{{ $bln }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tahun --}}
    <div>
        <label class="block mb-2 text-sm font-medium">Tahun</label>
        <select id="tahun" class="w-full border p-2 rounded">
            <option value="">Pilih Tahun</option>
            @for($i = 2000; $i <= date('Y'); $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>

</div>

    {{-- INFO --}}
    <div id="infoCard" class="hidden mt-6 p-4 border rounded bg-gray-50">
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Kecamatan</p>
                <p id="infoKecamatan">-</p>
            </div>
            <div>
                <p class="text-gray-500">Nama KUBE</p>
                <p id="infoNama">-</p>
            </div>
            <div>
                <p class="text-gray-500">Pendamping</p>
                <p id="infoPendamping" data-id-pendamping="0">-</p>
            </div>
        </div>
    </div>

    <div class="flex justify-center mt-6">
        <button id="openModalBtn" class="bg-blue-600 text-white px-6 py-2 rounded">
            Mulai Prediksi
        </button>
    </div>

</div>

{{-- MODAL --}}
<div id="prediksiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded w-full max-w-xl max-h-[90vh] overflow-y-auto relative">

        {{-- CLOSE --}}
        <button id="closeModalBtn" class="absolute top-2 right-3 text-xl">&times;</button>

        <form id="prediksiForm" action="{{ route('prediksi.store') }}" method="POST">
            @csrf
            @method('post')

            <input type="hidden" name="id_kube" id="inputIdKube">
            <input type="hidden" name="id_pendamping" id="inputIdPendamping">
            <input type="hidden" name="bulan" id="inputBulan">
            <input type="hidden" name="tahun" id="inputTahun">

            @foreach($pertanyaan as $p)
            @php $pid = (int) $p->id_pertanyaan; @endphp

            <div class="border p-4 mb-4 rounded">

                <p class="mb-2 font-medium">
                    {{ $loop->iteration }}. {{ $p->pertanyaan }}
                </p>

                <div class="flex gap-6">

                    <label>
                        <input type="radio"
                               name="jawaban[[{{ $loop->iteration }}]]"
                               value="ya"
                               required>
                        Ya
                    </label>

                    <label>
                        <input type="radio"
                               name="jawaban[[{{ $loop->iteration }}]]"
                               value="tidak"
                               required>
                        Tidak
                    </label>

                </div>

                <input type="text"
                       name="catatan[[{{ $loop->iteration }}]]"
                       class="w-full border p-2 mt-2"
                       placeholder="Keterangan..."
                       required>

            </div>
            @endforeach

            <button type="submit" class="bg-green-600 text-white px-4 py-2">
                Simpan & Hitung
            </button>

        </form>
    </div>
</div>

<script>

// ELEMENT
const kecamatan = document.getElementById('kecamatan');
const kube = document.getElementById('kube');
const bulan = document.getElementById('bulan');
const tahun = document.getElementById('tahun');

const modal = document.getElementById('prediksiModal');
const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.getElementById('closeModalBtn');

const inputIdKube = document.getElementById('inputIdKube');
const inputIdPendamping = document.getElementById('inputIdPendamping');
const inputBulan = document.getElementById('inputBulan');
const inputTahun = document.getElementById('inputTahun');

const infoPendamping = document.getElementById('infoPendamping');

// LOAD KUBE
kecamatan.addEventListener('change', function () {
    fetch(`/get-kube/${this.value}`)
        .then(res => res.json())
        .then(data => {
            kube.innerHTML = '<option value="">Pilih KUBE</option>';
            data.forEach(k => {
                kube.innerHTML += `<option value="${k.id_kube}">${k.nama_kube}</option>`;
            });
        });
});

// DETAIL KUBE
kube.addEventListener('change', function () {
    fetch(`/get-kube-detail/${this.value}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('infoCard').classList.remove('hidden');
            document.getElementById('infoKecamatan').innerText = data.nama_kecamatan ?? '-';
            document.getElementById('infoNama').innerText = data.nama_kube ?? '-';
            infoPendamping.innerText = data.nama_pendamping ?? '-';
            infoPendamping.dataset.idPendamping = data.id_pendamping ?? 0;
        });
});

// OPEN MODAL
openBtn.addEventListener('click', function () {

    if (!kube.value || !bulan.value || !tahun.value) {
        alert('Lengkapi data dulu!');
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
});

// CLOSE MODAL
closeBtn.addEventListener('click', function () {
    modal.classList.add('hidden');
});

// SUBMIT
document.getElementById('prediksiForm').addEventListener('submit', function () {

    inputIdKube.value = kube.value;
    inputBulan.value = bulan.value;
    inputTahun.value = tahun.value;
    inputIdPendamping.value = infoPendamping.dataset.idPendamping || 0;

});

</script>

@stop