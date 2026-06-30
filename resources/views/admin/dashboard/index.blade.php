@extends('admin.layout')

@section('title', 'Dashboard KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Dashboard KUBE</span>
@stop

@section('content')

<!-- HERO SECTION -->

<div class="bg-gradient-to-r from-blue-700 to-cyan-600 rounded-3xl p-10 text-white mb-8">

<div class="grid md:grid-cols-2 gap-8 items-center">

    <div>
        <h1 class="text-5xl font-bold mb-5">
            Kelompok Usaha Bersama (KUBE)
        </h1>

        <p class="text-lg leading-8 mb-6">
            Program pemberdayaan masyarakat untuk meningkatkan kesejahteraan
            melalui usaha produktif yang dilakukan secara berkelompok.
            Bergabunglah bersama KUBE dan wujudkan kemandirian ekonomi masyarakat.
        </p>
    </div>

    <div>
        <img src="{{ asset('images/pendamping-kube5.jpeg') }}"
            class="w-full h-[350px] object-cover rounded-2xl shadow-xl">
    </div>

</div>

</div>

<!-- STATISTIK -->

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

<div class="bg-white rounded-2xl shadow p-6 text-center">
    <div class="text-5xl mb-3">🏢</div>
    <h3 class="text-4xl font-bold text-cyan-700">100</h3>
    <p class="text-gray-500">Total KUBE</p>
</div>

<div class="bg-white rounded-2xl shadow p-6 text-center">
    <div class="text-5xl mb-3">🌱</div>
    <h3 class="text-4xl font-bold text-green-600">90</h3>
    <p class="text-gray-500">KUBE Aktif</p>
</div>

<div class="bg-white rounded-2xl shadow p-6 text-center">
    <div class="text-5xl mb-3">👨‍💼</div>
    <h3 class="text-4xl font-bold text-blue-600">25</h3>
    <p class="text-gray-500">Pendamping</p>
</div>

<div class="bg-white rounded-2xl shadow p-6 text-center">
    <div class="text-5xl mb-3">📁</div>
    <h3 class="text-4xl font-bold text-red-500">12</h3>
    <p class="text-gray-500">Kategori Usaha</p>
</div>

</div>

</div>
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 class="text-xl font-bold mb-4">
        Grafik Perkembangan KUBE
    </h3>

    <div style="height:250px">
        <canvas id="perkembanganChart"></canvas>
    </div>
</div>
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 class="text-lg font-bold mb-4">
        Jumlah KUBE per Kecamatan
    </h3>

    <div style="height:300px">
        <canvas id="kecamatanChart"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold mb-4">
            Kategori Usaha
        </h3>

        <div style="height:250px">
            <canvas id="kategoriChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-bold mb-4">
            Status KUBE
        </h3>

        <div style="height:250px">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

</div>
<!-- GALERI -->

<div class="bg-white rounded-2xl shadow p-8 mb-10">

<div class="flex justify-between items-center mb-6">

    <h2 class="text-3xl font-bold">
        Galeri Kegiatan KUBE
    </h2>

    <div class="flex justify-between items-center mb-6">

    @if(Auth::user()->role == 'admin')

    <a href="{{ route('galeri.index') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
        Kelola Galeri
    </a>
    @endif
</div>

</div>
<!-- CARD GALERI -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    @forelse($galeri as $item)

    <div class="rounded-2xl overflow-hidden shadow border">

        <a href="{{ route('galeri.detail', $item->id_galeri) }}">
        <img src="{{ asset('images/' . $item->gambar) }}"
                class="w-full h-56 object-cover hover:scale-105 transition duration-300">
        </a>

        <div class="p-4">

            <h3 class="font-semibold text-lg">
                {{ $item->judul }}
            </h3>

            <p class="text-sm text-gray-500 mt-2">
                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
            </p>

            <p class="text-sm text-gray-600 mt-2">
                {{ $item->deskripsi }}
            </p>
            
        </div>
    
    </div>

    @empty

    <div class="col-span-3 text-center py-10 text-gray-500">
        Belum ada data galeri.
    </div>

    @endforelse

</div>

<div class="h-10"></div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('kubeChart');

new Chart(document.getElementById('perkembanganChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
        datasets: [{
            label: 'Jumlah KUBE',
            data: [40,55,70,80,90,100]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>
<script>

// Grafik Kecamatan
new Chart(document.getElementById('kecamatanChart'), {
    type: 'bar',
    data: {
        labels: [
            'Kec. A',
            'Kec. B',
            'Kec. C',
            'Kec. D',
            'Kec. E'
        ],
        datasets: [{
            label: 'Jumlah KUBE',
            data: [20, 15, 30, 25, 18]
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false
    }
});

// Grafik Kategori
new Chart(document.getElementById('kategoriChart'), {
    type: 'pie',
    data: {
        labels: [
            'Kuliner',
            'Pertanian',
            'Peternakan',
            'Perdagangan'
        ],
        datasets: [{
            data: [30, 25, 20, 25]
        }]
    }
});

// Grafik Status
new Chart(document.getElementById('statusChart'), {
    type: 'bar',
    data: {
        labels: [
            'Aktif',
            'Tidak Aktif'
        ],
        datasets: [{
            label: 'Jumlah',
            data: [85, 15]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

</script>
@stop