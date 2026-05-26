@extends('pendamping.dashboard.index')

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
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

    {{-- PILIH DATA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

        {{-- Kecamatan --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Kecamatan</label>
            <select id="kecamatan"
                    class="w-full border border-gray-300 p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    disabled>
                <option value="">Pilih Kecamatan</option>
                @foreach($kecamatan as $kec)
                    <option value="{{ $kec->id_kecamatan }}" selected>
                        {{ $kec->nama_kecamatan }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-2">
                Kecamatan ditentukan otomatis berdasarkan akun pendamping yang login.
            </p>
        </div>

        {{-- KUBE --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Nama KUBE</label>
            <select id="kube" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih KUBE</option>
            </select>
            <p class="text-xs text-gray-500 mt-2">
                Data KUBE ditampilkan sesuai pembagian pendamping yang aktif.
            </p>
        </div>

        {{-- Bulan --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Bulan</label>
            <select id="bulan" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Bulan</option>
            </select>
            <p class="text-xs text-gray-500 mt-2">
                Informasi:
                pilih tahun terlebih dahulu sebagai acuan karena bulan yang sudah pernah diprediksi
                pada tahun yang dipilih tidak akan ditampilkan.
            </p>
        </div>

        {{-- Tahun --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-gray-700">Tahun</label>
            <select id="tahun" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Tahun</option>
                @for($i = date('Y') - 2; $i <= date('Y'); $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

    </div>

    {{-- INFO --}}
    <div id="infoCard" class="hidden mt-6 p-5 border border-gray-300 rounded-xl bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center">

            {{-- Kecamatan --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Kecamatan</p>
                <p id="infoKecamatan" class="font-semibold text-gray-800 text-base mt-1">-</p>
            </div>

            {{-- Nama KUBE --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Nama KUBE</p>
                <p id="infoNama" class="font-semibold text-gray-800 text-base mt-1">-</p>
            </div>

            {{-- Pendamping --}}
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Pendamping</p>
                <p id="infoPendamping" class="font-semibold text-gray-800 text-base mt-1">-</p>
            </div>

            {{-- Tombol --}}
            <div class="flex flex-col items-start md:items-center md:justify-end">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-2 text-left md:text-right">
                    Data Pendukung
                </p>
                <a id="btnPerkembanganUsaha"
                   href="#"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-500 text-white rounded-lg transition font-medium pointer-events-none opacity-50">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    Lihat Perkembangan
                </a>
            </div>

        </div>
    </div>

    <div class="flex justify-center mt-6">
        <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm transition">
            Mulai Prediksi
        </button>
    </div>

</div>

{{-- MODAL --}}
<div id="prediksiModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-full max-w-xl max-h-[90vh] overflow-y-auto relative shadow-lg">

        {{-- CLOSE --}}
        <button id="closeModalBtn" class="absolute top-2 right-3 text-2xl text-gray-500 hover:text-gray-700">
            &times;
        </button>

        <form id="prediksiForm" action="{{ route('prediksi.store') }}" method="POST">
            @csrf
            @method('post')

            <input type="hidden" name="id_kube" id="inputIdKube">
            <input type="hidden" name="bulan" id="inputBulan">
            <input type="hidden" name="tahun" id="inputTahun">

            @foreach($pertanyaan as $p)
                <div class="border border-gray-200 p-4 mb-4 rounded-lg">
                    <p class="mb-3 font-medium text-gray-800">
                        {{ $loop->iteration }}. {{ $p->pertanyaan }}
                    </p>

                    <div class="flex gap-6 mb-3">
                        <label class="cursor-pointer flex items-center gap-2">
                            <input type="radio"
                                   name="jawaban[{{ $p->id }}]"
                                   value="ya"
                                   required>
                            <span>Ya</span>
                        </label>

                        <label class="cursor-pointer flex items-center gap-2">
                            <input type="radio"
                                   name="jawaban[{{ $p->id }}]"
                                   value="tidak"
                                   required>
                            <span>Tidak</span>
                        </label>
                    </div>

                    <input type="text"
                           name="catatan[{{ $p->id }}]"
                           class="w-full border border-gray-300 p-2.5 rounded-lg"
                           placeholder="Keterangan untuk pertanyaan {{ $loop->iteration }}...">
                </div>
            @endforeach

            <div class="flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    Simpan & Hitung
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ==================== ELEMENT ====================
const kecamatan = document.getElementById('kecamatan');
const kube = document.getElementById('kube');
const bulan = document.getElementById('bulan');
const tahun = document.getElementById('tahun');

const modal = document.getElementById('prediksiModal');
const openBtn = document.getElementById('openModalBtn');
const closeBtn = document.getElementById('closeModalBtn');

const inputIdKube = document.getElementById('inputIdKube');
const inputBulan = document.getElementById('inputBulan');
const inputTahun = document.getElementById('inputTahun');

const infoCard = document.getElementById('infoCard');
const infoKecamatan = document.getElementById('infoKecamatan');
const infoNama = document.getElementById('infoNama');
const infoPendamping = document.getElementById('infoPendamping');
const btnPerkembanganUsaha = document.getElementById('btnPerkembanganUsaha');

// ==================== DATA BULAN ====================
const daftarBulan = [
    { value: 1, label: 'Januari' },
    { value: 2, label: 'Februari' },
    { value: 3, label: 'Maret' },
    { value: 4, label: 'April' },
    { value: 5, label: 'Mei' },
    { value: 6, label: 'Juni' },
    { value: 7, label: 'Juli' },
    { value: 8, label: 'Agustus' },
    { value: 9, label: 'September' },
    { value: 10, label: 'Oktober' },
    { value: 11, label: 'November' },
    { value: 12, label: 'Desember' }
];

// ==================== HELPER ====================
function resetInfoCard() {
    infoCard.classList.add('hidden');
    infoKecamatan.innerText = '-';
    infoNama.innerText = '-';
    infoPendamping.innerText = '-';

    btnPerkembanganUsaha.setAttribute('href', '#');
    btnPerkembanganUsaha.classList.add('pointer-events-none', 'opacity-50');
}

function resetKube(pesan = 'Pilih KUBE') {
    kube.innerHTML = `<option value="">${pesan}</option>`;
}

function resetBulan(pesan = 'Pilih Bulan') {
    bulan.innerHTML = `<option value="">${pesan}</option>`;
}

// ==================== LOAD KUBE DARI PEMBAGIAN PENDAMPING ====================
function loadKubePendamping() {
    resetKube();
    resetBulan();
    resetInfoCard();

    fetch(`/get-kube`)
        .then(res => res.json())
        .then(data => {
            if (!data || data.length === 0) {
                resetKube('Belum ada KUBE yang ditugaskan');
                return;
            }

            data.forEach(k => {
                kube.innerHTML += `<option value="${k.id_kube}">${k.nama_kube}</option>`;
            });
        })
        .catch(error => {
            console.error('Gagal mengambil data KUBE:', error);
            resetKube('Gagal memuat KUBE');
        });
}

// ==================== DETAIL KUBE ====================
kube.addEventListener('change', function () {
    resetBulan();

    if (!this.value) {
        resetInfoCard();
        return;
    }

    fetch(`/get-kube-detail/${this.value}`)
        .then(res => res.json())
        .then(data => {
            infoCard.classList.remove('hidden');
            infoKecamatan.innerText = data.nama_kecamatan ?? '-';
            infoNama.innerText = data.nama_kube ?? '-';
            infoPendamping.innerText = data.nama_pendamping ?? '-';

            btnPerkembanganUsaha.setAttribute('href', `/admin/perkembangan-usaha?id_kube=${this.value}`);
            btnPerkembanganUsaha.classList.remove('pointer-events-none', 'opacity-50');

            loadBulanTersedia();
        })
        .catch(error => {
            console.error('Gagal mengambil detail KUBE:', error);
            resetInfoCard();
        });
});

// ==================== LOAD BULAN TERSEDIA ====================
function loadBulanTersedia() {
    const idKube = kube.value;
    const tahunValue = tahun.value;

    resetBulan();

    if (!idKube || !tahunValue) {
        return;
    }

    fetch(`/pendamping/prediksi/bulan-tersedia?id_kube=${idKube}&tahun=${tahunValue}`)
        .then(response => response.json())
        .then(data => {
            const bulanTerpakai = (data.bulan_terpakai || []).map(Number);
            const bulanTersedia = daftarBulan.filter(item => !bulanTerpakai.includes(item.value));

            if (bulanTersedia.length === 0) {
                resetBulan('Semua bulan sudah diprediksi');
                return;
            }

            bulanTersedia.forEach(item => {
                bulan.innerHTML += `<option value="${item.value}">${item.label}</option>`;
            });
        })
        .catch(error => {
            console.error('Gagal mengambil data bulan tersedia:', error);
        });
}

// reload bulan kalau tahun diganti
tahun.addEventListener('change', function () {
    loadBulanTersedia();
});

// ==================== OPEN MODAL ====================
openBtn.addEventListener('click', function () {
    if (!kube.value || !bulan.value || !tahun.value) {
        alert('Lengkapi data dulu!');
        return;
    }

    if (bulan.options[bulan.selectedIndex]?.text === 'Semua bulan sudah diprediksi') {
        alert('Semua bulan pada tahun ini sudah diprediksi untuk KUBE yang dipilih.');
        return;
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
});

// ==================== CLOSE MODAL ====================
closeBtn.addEventListener('click', function () {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
});

// ==================== SUBMIT ====================
document.getElementById('prediksiForm').addEventListener('submit', function () {
    inputIdKube.value = kube.value;
    inputBulan.value = bulan.value;
    inputTahun.value = tahun.value;
});

// ==================== AUTO LOAD KUBE SAAT HALAMAN DIBUKA ====================
document.addEventListener('DOMContentLoaded', function () {
    loadKubePendamping();
});
</script>

@stop