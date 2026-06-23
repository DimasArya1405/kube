{{-- Ranking KUBE --}}

@extends(auth()->user()->role . '.layout')

@section('title', 'Ranking KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Ranking KUBE</span>
@stop

@section('content')

@php
    $adaFilter = request()->hasAny(['kecamatan','tahun','kategori','status'])
        && array_filter(request()->only(['kecamatan','tahun','kategori','status']));

    // Fallback jika controller belum mengirim variabel ini (mis. view dipanggil dari tempat lain)
    $hideNominal = $hideNominal ?? false;
@endphp

{{-- HEADER --}}
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Ranking KUBE</h2>
        <p class="text-gray-500 mt-1">Ranking KUBE terbaik berdasarkan laba bersih.</p>
    </div>
</div>

{{-- CHART TOP 10 (disembunyikan untuk Ketua KUBE karena berbasis nominal) --}}
@unless($hideNominal)
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
@endunless

{{-- TOOLBAR: SEARCH (kiri) + FILTER + EKSPOR (kanan), dibungkus card seperti Pencairan Bantuan --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4 overflow-x-auto">
    <form method="GET" class="flex flex-col md:flex-row md:items-end gap-3">

        {{-- Search di kiri, kaya di Koordinator --}}
        <div class="relative flex-1 min-w-[140px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
            <span class="absolute inset-y-0 left-3 top-6 flex items-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
            </span>
            <input type="text" id="searchInput" placeholder="Cari KUBE..."
                class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="flex-1 min-w-[120px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
            <select name="kecamatan"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Kecamatan</option>
                @foreach($kecamatanList as $k)
                    <option value="{{ $k->id_kecamatan }}" {{ request('kecamatan') == $k->id_kecamatan ? 'selected' : '' }}>
                        {{ $k->nama_kecamatan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[110px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
            <select name="tahun"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Tahun</option>
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[120px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
            <select name="kategori"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat->id_kategori }}" {{ request('kategori') == $kat->id_kategori ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 min-w-[110px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">Semua Status</option>
                <option value="Aktif"       {{ request('status') == 'Aktif'       ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="flex gap-2 flex-shrink-0">
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm whitespace-nowrap transition">
                Filter
            </button>

            @if($adaFilter)
            <a href="{{ route('ranking.kube') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm whitespace-nowrap transition">
                Reset
            </a>
            @endif
        </div>

        {{-- Ekspor PDF & Excel, style disamakan dengan Koordinator --}}
        <div class="flex gap-2 flex-shrink-0">
            <a href="{{ route('ranking.kube.export.pdf', request()->query()) }}" download
                class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg whitespace-nowrap">
                Ekspor PDF
            </a>

            <a href="{{ route('ranking.kube.export.excel', request()->query()) }}"
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg whitespace-nowrap">
                Ekspor Excel
            </a>
        </div>
    </form>
</div>

@if(request('tahun') || request('status') || request('kecamatan') || request('kategori'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan data filter:
        @if(request('kecamatan')) Kecamatan <span class="font-semibold text-gray-800">{{ optional($kecamatanList->firstWhere('id_kecamatan', request('kecamatan')))->nama_kecamatan }}</span> @endif
        @if(request('tahun')) Tahun <span class="font-semibold text-gray-800">{{ request('tahun') }}</span> @endif
        @if(request('kategori')) Kategori <span class="font-semibold text-gray-800">{{ optional($kategoriList->firstWhere('id_kategori', request('kategori')))->nama_kategori }}</span> @endif
        @if(request('status')) Status <span class="font-semibold text-gray-800">{{ request('status') }}</span> @endif
    </div>
@endif

{{-- TABEL --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="overflow-x-auto" style="scrollbar-width: thin;">
        <table class="min-w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-4 py-3">Nama KUBE</th>
                    <th class="px-4 py-3">Cluster</th>
                    <th class="px-4 py-3">Kecamatan</th>
                    @unless($hideNominal)
                    <th class="px-4 py-3 whitespace-nowrap">Total Omset</th>
                    <th class="px-4 py-3 whitespace-nowrap">Total Pengeluaran</th>
                    <th class="px-4 py-3 whitespace-nowrap">Total Laba Bersih</th>
                    @endunless
                    <th class="px-4 py-3 whitespace-nowrap">Status</th>
                    <th class="px-4 py-3 text-center whitespace-nowrap" style="min-width:110px">
                        Peringkat
                        @if($adaFilter)
                            <br><span class="font-normal text-xs opacity-80">(Keseluruhan)</span>
                        @endif
                    </th>
                    @if($adaFilter)
                    <th class="px-4 py-3 text-center whitespace-nowrap" style="min-width:90px">
                        Peringkat<br>
                        <span class="font-normal text-xs opacity-80">(Filter)</span>
                    </th>
                    @endif
                    <th class="px-4 py-3 text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($filtered as $item)
                <tr class="border-t border-gray-100 hover:bg-gray-50 transition searchable-row">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $item->nama_kube }}</td>
                    <td class="px-4 py-3">{{ $item->nama_cluster }}</td>
                    <td class="px-4 py-3">{{ $item->nama_kecamatan }}</td>
                    @unless($hideNominal)
                    <td class="px-4 py-3 whitespace-nowrap">Rp. {{ number_format($item->total_omset, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">Rp. {{ number_format($item->total_pengeluaran, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 font-semibold text-gray-800 whitespace-nowrap">Rp. {{ number_format($item->total_laba_bersih, 0, ',', '.') }}</td>
                    @endunless
                    <td class="px-4 py-3">
                        @if($item->status === 'Aktif')
                            <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded">Aktif</span>
                        @else
                            <span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded whitespace-nowrap">Tidak Aktif</span>
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
                    <td colspan="{{ $adaFilter ? 10 : 9 }}" class="px-6 py-8 text-center text-gray-400 italic">
                        Tidak ada data yang ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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

            <div class="flex justify-end p-4 border-t">
                <button onclick="closeModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">Tutup</button>
            </div>

        </div>
    </div>
</div>

{{-- CHART JS (hanya dimuat jika nominal ditampilkan) --}}
@unless($hideNominal)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endunless

<script>
// ===== CHART =====
const hideNominal = {{ $hideNominal ? 'true' : 'false' }};

@unless($hideNominal)
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
                maxBarThickness: 60,
            },
            {
                label: 'Total Laba Bersih',
                data: top10.map(d => d.total_laba_bersih),
                backgroundColor: '#FB923C',
                borderRadius: 4,
                maxBarThickness: 60,
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
            x: {
                grid: { display: false },
                categoryPercentage: 0.5,
                barPercentage: 0.9,
            }
        }
    }
});
@endunless

// ===== SEARCH (client-side, kaya di Koordinator) =====
document.getElementById('searchInput').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('.searchable-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
    });
});

// ===== MODAL DETAIL =====
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

    let nominalHtml = '';
    if (!hideNominal) {
        nominalHtml =
            field('Total Omset',       fmt(data.total_omset)) +
            field('Total Pengeluaran', fmt(data.total_pengeluaran)) +
            field('Total Laba Bersih', fmt(data.total_laba_bersih));
    }

    document.getElementById('modalContent').innerHTML =
        field('Nama KUBE',         data.nama_kube) +
        field('Kecamatan',         data.nama_kecamatan) +
        field('Desa/Kelurahan',    data.nama_desa_kelurahan) +
        field('Kategori',          data.nama_kategori) +
        field('Cluster',           data.nama_cluster) +
        field('Periode',           data.periode) +
        nominalHtml +
        field('Status',            data.status) +
        rankingHtml;
}

function closeModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}

document.getElementById('modalDetail').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

@stop