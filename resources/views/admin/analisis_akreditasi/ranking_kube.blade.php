{{-- Ranking KUBE --}}

@extends('admin.layout')

@section('title', 'Ranking KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Ranking KUBE</span>
@stop

@section('content')

@php
    $adaFilter = request()->hasAny(['kecamatan','tahun','kategori','status'])
        && array_filter(request()->only(['kecamatan','tahun','kategori','status']));
@endphp

{{-- HEADER --}}
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Ranking KUBE</h2>
        <p class="text-gray-500 mt-1">Ranking KUBE terbaik berdasarkan laba bersih.</p>
    </div>
   
    <div class="flex gap-2">
        {{-- Tombol PDF --}}
        <button onclick="previewPDF('{{ route('ranking.kube.export.pdf', request()->query()) }}')"
            class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            Ekspor PDF
        </button>

        {{-- Tombol Excel --}}
        <a href="{{ route('ranking.kube.export.excel', request()->query()) }}"
        class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10"/>
            </svg>
            Ekspor Excel
        </a>
    </div>
</div>

{{-- CHART TOP 10 --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-semibold text-gray-700">Daftar 10 KUBE Terbaik</h3>
        <div class="flex gap-4 text-xs text-gray-500">
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm bg-[#29ABE2] inline-block"></span> Total Omset
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-3 h-3 rounded-sm bg-orange-400 inline-block"></span> Total Laba Bersih
            </span>
        </div>
    </div>
    <canvas id="chartTop10" height="90"></canvas>
</div>

{{-- FILTER --}}
<form method="GET" class="flex flex-wrap items-center gap-3 mb-4">
    <select name="kecamatan"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Kecamatan</option>
        @foreach($kecamatanList as $k)
            <option value="{{ $k->id_kecamatan }}" {{ request('kecamatan') == $k->id_kecamatan ? 'selected' : '' }}>
                {{ $k->nama_kecamatan }}
            </option>
        @endforeach
    </select>

    <select name="tahun"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Tahun</option>
        @foreach($tahunList as $t)
            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
    </select>

    <select name="kategori"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Kategori</option>
        @foreach($kategoriList as $kat)
            <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                {{ $kat->nama_kategori }}
            </option>
        @endforeach
    </select>

    <select name="status"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="">Semua Status</option>
        <option value="Aktif"       {{ request('status') == 'Aktif'       ? 'selected' : '' }}>Aktif</option>
        <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
    </select>

    <button type="submit"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
        </svg>
        Filter
    </button>

    @if($adaFilter)
    <a href="{{ route('ranking.kube') }}"
       class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        Reset
    </a>
    @endif
</form>

{{-- TABEL --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-4 py-3">Nama KUBE</th>
                    <th class="px-4 py-3">Cluster</th>
                    <th class="px-4 py-3">Kecamatan</th>
                    <th class="px-4 py-3">Total Omset</th>
                    <th class="px-4 py-3">Total Pengeluaran</th>
                    <th class="px-4 py-3">Total Laba Bersih</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">
                        Peringkat
                        @if($adaFilter)
                            <br><span class="font-normal text-xs opacity-80">(Keseluruhan)</span>
                        @endif
                    </th>
                    @if($adaFilter)
                    <th class="px-4 py-3 text-center">
                        Peringkat<br>
                        <span class="font-normal text-xs opacity-80">(Filter)</span>
                    </th>
                    @endif
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filtered as $item)
                <tr class="border-t border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $item->nama_kube }}</td>
                    <td class="px-4 py-3">{{ $item->nama_cluster }}</td>
                    <td class="px-4 py-3">{{ $item->nama_kecamatan }}</td>
                    <td class="px-4 py-3">Rp. {{ number_format($item->total_omset, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">Rp. {{ number_format($item->total_pengeluaran, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-800">Rp. {{ number_format($item->total_laba_bersih, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @if($item->status === 'Aktif')
                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>
                        @else
                            <span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center font-bold text-gray-700">
                        {{ $item->ranking_overall }}
                    </td>
                    @if($adaFilter)
                    <td class="px-4 py-3 text-center font-bold text-gray-700">
                        {{ $item->ranking_filter }}
                    </td>
                    @endif
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick='openModal(@json($item))'
                                class="text-blue-500 hover:text-blue-700 transition" title="Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $adaFilter ? 10 : 9 }}" class="px-6 py-8 text-center text-gray-400">
                        Tidak ada data yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL PREVIEW PDF --}}
<div id="modalPDF"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-xl shadow-xl w-[90vw] h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 border-b">
            <h3 class="font-semibold text-gray-800">Preview PDF — Ranking KUBE</h3>
            <div class="flex gap-2">
                <button onclick="closePDF()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>
        </div>
        <iframe id="iframePDF" src="" class="flex-1 rounded-b-xl"></iframe>
    </div>
</div>


{{-- MODAL DETAIL --}}
<div id="modalDetail"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">
    <div class="relative p-4 w-full max-w-2xl">
        <div class="bg-white rounded-lg shadow">

            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Detail KUBE</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Informasi lengkap KUBE.</p>
                </div>
                <button onclick="closeModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center text-lg">
                    ✕
                </button>
            </div>

            <div class="p-5 grid grid-cols-2 gap-4" id="modalContent"></div>

        </div>
    </div>
</div>

{{-- CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ===== CHART =====
const top10 = @json($top10->values());

new Chart(document.getElementById('chartTop10'), {
    type: 'bar',
    data: {
        labels: top10.map((d, i) => '#' + (i + 1) + ' ' + d.nama_kube),
        datasets: [
            {
                label: 'Total Omset',
                data: top10.map(d => d.total_omset),
                backgroundColor: '#29ABE2',
                borderRadius: 4,
            },
            {
                label: 'Total Laba Bersih',
                data: top10.map(d => d.total_laba_bersih),
                backgroundColor: '#FB923C',
                borderRadius: 4,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                ticks: {
                    callback: v => v >= 1000000 ? (v / 1000000) + 'jt' : v.toLocaleString('id-ID')
                },
                grid: { color: '#f3f4f6' }
            },
            x: { grid: { display: false } }
        }
    }
});

// ===== MODAL =====
const adaFilter = {{ $adaFilter ? 'true' : 'false' }};

function fmt(v) {
    return (v !== null && v !== undefined)
        ? 'Rp. ' + Number(v).toLocaleString('id-ID')
        : '-';
}

function field(label, value) {
    return `
        <div>
            <p class="text-xs font-semibold text-gray-500 mb-1">${label}</p>
            <div class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 bg-gray-50">
                ${value ?? '-'}
            </div>
        </div>`;
}

function openModal(data) {
    document.getElementById('modalDetail').classList.remove('hidden');

    let rankingHtml = field('Peringkat (Keseluruhan)', data.ranking_overall);
    if (adaFilter) {
        rankingHtml += field('Peringkat (Filter)', data.ranking_filter ?? '-');
    }

// SESUDAH
document.getElementById('modalContent').innerHTML =
    field('Nama KUBE',         data.nama_kube) +
    field('Kecamatan',         data.nama_kecamatan) +
    field('Desa/Kelurahan',    data.nama_desa_kelurahan) +
    field('Kategori',          data.nama_kategori) +
    field('Cluster',           data.nama_cluster) +
    field('Periode',           data.periode) +          // ← tambah ini
    field('Total Omset',       fmt(data.total_omset)) +
    field('Total Pengeluaran', fmt(data.total_pengeluaran)) +
    field('Total Laba Bersih', fmt(data.total_laba_bersih)) +
    field('Status',            data.status) +
    rankingHtml;
}

function closeModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}

document.getElementById('modalDetail').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});


function previewPDF(url) {
    document.getElementById('iframePDF').src = url;
     document.getElementById('modalPDF').classList.remove('hidden');
}
function closePDF() {
    document.getElementById('iframePDF').src = '';
    document.getElementById('modalPDF').classList.add('hidden');
}
</script>

@stop