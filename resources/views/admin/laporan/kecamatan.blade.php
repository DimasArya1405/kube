@extends('admin.layout')

@section('title', 'Laporan Kecamatan - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Laporan Kecamatan</span>
@stop

@section('content')

{{-- ===== HEADER ===== --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Laporan Kecamatan</h2>
        <p class="text-gray-500 mt-1">Berdasarkan Pengajuan yang Telah Disetujui.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('laporan.kecamatan.excel', request()->query()) }}"
           class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition shadow-sm font-medium">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('laporan.kecamatan.pdf', request()->query()) }}"
           class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md transition shadow-sm font-medium">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>
</div>

{{-- ===== SUMMARY CARDS ===== --}}
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

    <div class="bg-white p-4 rounded-lg shadow border">
        <p class="text-sm text-gray-500">Total KUBE</p>
        <h3 class="text-2xl font-bold text-gray-800">{{ $totalKube ?? 0 }}</h3>
    </div>

    <div class="bg-green-50 p-4 rounded-lg shadow border border-green-200">
        <p class="text-sm text-green-600">KUBE Aktif</p>
        <h3 class="text-2xl font-bold text-green-700">{{ $kubeAktif ?? 0 }}</h3>
    </div>

    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600">KUBE Nonaktif</p>
        <h3 class="text-2xl font-bold text-red-700">{{ $kubeNonaktif ?? 0 }}</h3>
    </div>

    <div class="bg-white p-4 rounded-lg shadow border">
        <p class="text-sm text-gray-500">Total Omset</p>
        <h3 class="text-lg font-bold text-green-600">
            Rp {{ number_format($totalOmset ?? 0, 0, ',', '.') }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded-lg shadow border">
        <p class="text-sm text-gray-500">Total Laba</p>
        <h3 class="text-lg font-bold text-blue-600">
            Rp {{ number_format($totalLaba ?? 0, 0, ',', '.') }}
        </h3>
    </div>

</div>

{{-- ===== FILTER ===== --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <form action="{{ route('laporan.kecamatan') }}" method="GET">
        <div class="flex flex-col md:flex-row gap-4 md:items-end">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter Tahun</label>
                <select name="tahun"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[160px] text-sm">
                    <option value="">Semua Tahun</option>
                    @foreach($tahun as $t)
                        <option value="{{ $t->tahun }}" {{ request('tahun') == $t->tahun ? 'selected' : '' }}>
                            {{ $t->tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                <select name="kecamatan"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[160px] text-sm">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id_kecamatan }}" {{ request('kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cluster</label>
                <select name="cluster"
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 min-w-[160px] text-sm">
                    <option value="">Semua Cluster</option>
                    @foreach($cluster as $c)
                        <option value="{{ $c->id_cluster }}" {{ request('cluster') == $c->id_cluster ? 'selected' : '' }}>
                            {{ $c->nama_cluster }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm transition">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('laporan.kecamatan') }}"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm transition">
                    Reset
                </a>
            </div>

        </div>
    </form>
</div>

@if(request('tahun') || request('kecamatan') || request('cluster'))
    <div class="mb-4 text-sm text-gray-600">
        Menampilkan data filter:
        @if(request('tahun')) Tahun <span class="font-semibold text-gray-800">{{ request('tahun') }}</span> @endif
        @if(request('kecamatan')) Kecamatan <span class="font-semibold text-gray-800">{{ request('kecamatan') }}</span> @endif
        @if(request('cluster')) Cluster <span class="font-semibold text-gray-800">{{ request('cluster') }}</span> @endif
    </div>
@endif

{{-- ===== TABEL UTAMA ===== --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama KUBE</th>
                    <th class="px-6 py-3">Kecamatan</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3">Cluster</th>
                    <th class="px-6 py-3">Perkembangan</th>
                    <th class="px-6 py-3">Omset</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $d)
                <tr class="border-b hover:bg-gray-50 transition-colors">

                    <td class="px-6 py-4">{{ $loop->iteration }}</td>

                    <td class="px-6 py-4 font-medium text-gray-900">{{ $d->nama_kube }}</td>

                    <td class="px-6 py-4">{{ $d->nama_kecamatan }}</td>

                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs bg-indigo-100 text-indigo-700">
                            {{ $d->nama_kategori ?? '-' }}
                        </span>
                    </td>

                    <td class="px-6 py-4">{{ $d->nama_cluster }}</td>

                    <td class="px-6 py-4">
                        @if($d->perkembangan_usaha == 'Meningkat')
                            <span class="px-2 py-1 rounded text-white bg-green-500">Meningkat</span>
                        @elseif($d->perkembangan_usaha == 'Menurun')
                            <span class="px-2 py-1 rounded text-white bg-red-500">Menurun</span>
                        @elseif($d->perkembangan_usaha == 'Tetap')
                            <span class="px-2 py-1 rounded text-white bg-gray-400">Tetap</span>
                        @else
                            <span class="px-2 py-1 rounded text-white bg-yellow-500">Belum Ada Data</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 font-mono text-green-700 font-semibold">
                        Rp {{ number_format($d->total_omset, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4">
                        @if($d->status == 'Aktif')
                            <span class="px-2 py-1 rounded text-white bg-emerald-600">Aktif</span>
                        @else
                            <span class="px-2 py-1 rounded text-white bg-red-500">Tidak Aktif</span>
                        @endif
                    </td>

                    <td class="px-6 py-4">
                        <button
                            onclick="openModal(this)"
                            data-id="{{ $d->id_kube }}"
                            data-nama="{{ $d->nama_kube }}"
                            data-cluster="{{ $d->nama_cluster }}"
                            data-kecamatan="{{ $d->nama_kecamatan }}"
                            data-omset="{{ $d->total_omset }}"
                            data-laba="{{ $d->laba_bersih }}"
                            data-status="{{ $d->status }}"
                            data-pendamping="{{ $d->nama_pendamping }}"
                            data-desa="{{ $d->nama_desa_kelurahan }}"
                            data-tanggal="{{ $d->tanggal_terbentuk }}"
                            data-kategori="{{ $d->nama_kategori }}"
                            data-perkembangan="{{ $d->perkembangan_usaha }}"
                            class="px-3 py-1 rounded-md text-sm bg-blue-500 text-white hover:bg-blue-600 transition duration-200 ease-in-out">
                            Detail
                        </button>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-10 text-center text-gray-500 italic">
                        Tidak ada data laporan kecamatan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===== MODAL DETAIL ===== --}}
<div id="modalDetail"
     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">

    <div class="fixed inset-0" onclick="closeModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col z-10">

        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Detail KUBE</h3>
            <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            <div id="modalContent"></div>
        </div>

        <div class="p-4 border-t bg-gray-50 flex justify-between items-center">
            <a id="btnExportPdf" href="#"
               class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm transition">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
            <button type="button" onclick="closeModal()"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Tutup
            </button>
        </div>

    </div>
</div>

@push('scripts')
<script>
function openModal(button) {
    const d = button.dataset;

    document.getElementById('btnExportPdf').href = `/admin/laporan-kecamatan/pdf/${d.id}`;

    const perkembanganBadge = d.perkembangan === 'Meningkat'
        ? '<span class="px-2 py-1 rounded text-white bg-green-500 text-xs">Meningkat</span>'
        : d.perkembangan === 'Menurun'
        ? '<span class="px-2 py-1 rounded text-white bg-red-500 text-xs">Menurun</span>'
        : d.perkembangan === 'Tetap'
        ? '<span class="px-2 py-1 rounded text-white bg-gray-400 text-xs">Tetap</span>'
        : '<span class="px-2 py-1 rounded text-white bg-yellow-500 text-xs">Belum Ada Data</span>';

    const statusBadge = d.status.toLowerCase() === 'aktif'
        ? '<span class="px-2 py-1 rounded text-white bg-emerald-600 text-xs">Aktif</span>'
        : '<span class="px-2 py-1 rounded text-white bg-red-500 text-xs">Tidak Aktif</span>';

    document.getElementById('modalContent').innerHTML = `
        <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border mb-4">
            <div>
                <div class="text-xs text-gray-500 mb-1">Nama KUBE</div>
                <div class="font-semibold text-gray-800">${d.nama}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 mb-1">Status</div>
                ${statusBadge}
            </div>
            <div>
                <div class="text-xs text-gray-500 mb-1">Kecamatan</div>
                <div class="font-semibold text-gray-800">${d.kecamatan}</div>
            </div>
            <div>
                <div class="text-xs text-gray-500 mb-1">Cluster</div>
                <div class="font-semibold text-gray-800">${d.cluster}</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                <div class="text-sm text-green-700">Total Omset</div>
                <div class="text-xl font-bold text-green-800 mt-1">
                    Rp ${Number(d.omset).toLocaleString('id-ID')}
                </div>
            </div>
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                <div class="text-sm text-blue-700">Laba Bersih</div>
                <div class="text-xl font-bold text-blue-800 mt-1">
                    Rp ${Number(d.laba).toLocaleString('id-ID')}
                </div>
            </div>
        </div>

        <div class="bg-white border p-4 rounded-lg">
            <h4 class="font-semibold text-gray-700 mb-3">Informasi Tambahan</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Pendamping</div>
                    <div class="font-medium text-gray-800">${d.pendamping ?? '-'}</div>
                </div>
                <div>
                    <div class="text-gray-500">Desa</div>
                    <div class="font-medium text-gray-800">${d.desa ?? '-'}</div>
                </div>
                <div>
                    <div class="text-gray-500">Tanggal Terbentuk</div>
                    <div class="font-medium text-gray-800">${d.tanggal ?? '-'}</div>
                </div>
                <div>
                    <div class="text-gray-500">Kategori</div>
                    <div class="font-medium text-gray-800">${d.kategori ?? '-'}</div>
                </div>
                <div>
                    <div class="text-gray-500">Perkembangan</div>
                    ${perkembanganBadge}
                </div>
            </div>
        </div>
    `;

    document.getElementById('modalDetail').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new TomSelect('select[name="tahun"]', { create: false, allowEmptyOption: true });
    new TomSelect('select[name="kecamatan"]', { create: false, allowEmptyOption: true });
    new TomSelect('select[name="cluster"]', { create: false, allowEmptyOption: true });
});
</script>
@endpush

@endsection