@extends('kepala_dinas.layout')

@section('title', 'Dashboard Statistik - Kepala Dinas')

@section('breadcrumb')
<nav class="flex text-gray-700 mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
        <li class="inline-flex items-center text-gray-500">Kepala Dinas</li>
        <li>
            <div class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-800 font-bold">Dashboard Statistik</span>
            </div>
        </li>
    </ol>
</nav>
@stop

@section('content')

{{-- HEADER --}}
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-900">Dashboard Statistik KUBE</h2>
    <p class="text-gray-500 text-sm">Ringkasan data KUBE Kabupaten Cilacap</p>
</div>

{{-- KARTU STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <div class="bg-blue-500 text-white rounded-xl p-5 flex items-center justify-between shadow">
        <div>
            <p class="text-4xl font-extrabold">{{ $totalKube }}</p>
            <p class="text-sm font-semibold mt-1">Total KUBE</p>
        </div>
        <div class="bg-white/20 p-3 rounded-lg">
            <i data-lucide="store" class="w-8 h-8"></i>
        </div>
    </div>

    <div class="bg-green-500 text-white rounded-xl p-5 flex items-center justify-between shadow">
        <div>
            <p class="text-4xl font-extrabold">{{ $kubeAktif }}</p>
            <p class="text-sm font-semibold mt-1">KUBE Aktif</p>
        </div>
        <div class="bg-white/20 p-3 rounded-lg">
            <i data-lucide="check-circle" class="w-8 h-8"></i>
        </div>
    </div>

    <div class="bg-red-500 text-white rounded-xl p-5 flex items-center justify-between shadow">
        <div>
            <p class="text-4xl font-extrabold">{{ $kubeTidakAktif }}</p>
            <p class="text-sm font-semibold mt-1">KUBE Tidak Aktif</p>
        </div>
        <div class="bg-white/20 p-3 rounded-lg">
            <i data-lucide="x-circle" class="w-8 h-8"></i>
        </div>
    </div>

</div>

{{-- GRAFIK & TOP KECAMATAN --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- GRAFIK BAR PER KECAMATAN --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow border border-gray-200 p-5">
        <h3 class="text-base font-bold text-gray-800 mb-4">Rekap KUBE per Kecamatan</h3>
        <canvas id="chartKecamatan" height="120"></canvas>
    </div>

    {{-- DONUT STATUS --}}
    <div class="bg-white rounded-xl shadow border border-gray-200 p-5">
        <h3 class="text-base font-bold text-gray-800 mb-4">Status KUBE</h3>
        <canvas id="chartDonut" height="200"></canvas>
        <div class="flex justify-center gap-6 mt-4">
            <div class="text-center">
                <p class="text-2xl font-extrabold text-green-600">{{ $kubeAktif }}</p>
                <p class="text-xs text-gray-500">Aktif</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-extrabold text-red-500">{{ $kubeTidakAktif }}</p>
                <p class="text-xs text-gray-500">Tidak Aktif</p>
            </div>
        </div>
    </div>

</div>

{{-- TOP 5 KECAMATAN --}}
<div class="bg-white rounded-xl shadow border border-gray-200 p-5">
    <h3 class="text-base font-bold text-gray-800 mb-4">Top 5 Kecamatan dengan KUBE Terbanyak</h3>

    @forelse ($top5Kecamatan as $index => $item)
        <div class="flex items-center gap-3 mb-3">
            <div class="w-7 h-7 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold flex-shrink-0">
                {{ $index + 1 }}
            </div>
            <div class="flex-1">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-sm font-semibold text-gray-700">{{ $item->nama_kecamatan }}</span>
                    <span class="text-xs font-bold text-indigo-600">{{ $item->total }} KUBE</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-indigo-500 h-2 rounded-full"
                        style="width: {{ $maxTotal > 0 ? ($item->total / $maxTotal) * 100 : 0 }}%">
                    </div>
                </div>
                <div class="flex gap-4 mt-1">
                    <span class="text-xs text-green-600">Aktif: {{ $item->aktif }}</span>
                    <span class="text-xs text-red-500">Tidak Aktif: {{ $item->tidak_aktif }}</span>
                </div>
            </div>
        </div>
    @empty
        <p class="text-sm text-gray-500 text-center py-4">Belum ada data</p>
    @endforelse
</div>

@stop

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // CHART BAR PER KECAMATAN
    const ctxBar = document.getElementById('chartKecamatan').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Total KUBE',
                    data: @json($chartTotal),
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderRadius: 4,
                },
                {
                    label: 'Aktif',
                    data: @json($chartAktif),
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderRadius: 4,
                },
                {
                    label: 'Tidak Aktif',
                    data: @json($chartTidakAktif),
                    backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    borderRadius: 4,
                },
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 } } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // CHART DONUT STATUS
    const ctxDonut = document.getElementById('chartDonut').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Tidak Aktif'],
            datasets: [{
                data: [{{ $kubeAktif }}, {{ $kubeTidakAktif }}],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 } } }
            },
            cutout: '65%'
        }
    });
</script>
@endpush