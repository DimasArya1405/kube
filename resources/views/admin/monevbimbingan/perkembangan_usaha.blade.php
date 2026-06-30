@extends('admin.layout')

@section('title', 'Perkembangan Usaha - KUBE')

@section('content')



<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Perkembangan Usaha
            </h1>

            <p class="text-gray-500 mt-1">
                Kelola data perkembangan usaha seluruh KUBE.
            </p>
        </div>

    </div>


   {{-- CARD --}}
<div class="flex flex-wrap gap-5 mb-6">

    <div class="w-72 bg-white rounded-xl border border-blue-100 shadow-sm p-5">

        <p class="text-sm text-gray-500">
            Status Tercapai
        </p>

        <h2 class="mt-2 text-3xl font-bold text-blue-600">
            {{ $data->where('status_hasil','Tercapai')->count() }}
        </h2>

    </div>

    <div class="w-72 bg-white rounded-xl border border-green-100 shadow-sm p-5">

        <p class="text-sm text-gray-500">
            Perkembangan Meningkat
        </p>

        <h2 class="mt-2 text-3xl font-bold text-green-600">
            {{ $data->where('perkembangan_usaha','Meningkat')->count() }}
        </h2>

    </div>

    <div class="w-72 bg-white rounded-xl border border-yellow-100 shadow-sm p-5">

        <p class="text-sm text-gray-500">
            Total Omset
        </p>

        <h2 class="mt-2 text-2xl font-bold text-yellow-600">
            Rp {{ number_format($data->sum('total_omset'),0,',','.') }}
        </h2>

    </div>

</div>


    {{-- TOOLBAR --}}
    <div class="bg-white rounded-xl border shadow-sm p-4">

       <div class="flex items-center justify-between gap-4 mb-6">

    {{-- Kiri --}}
    <div class="flex items-center gap-3">

        {{-- Search --}}
        <input
            type="text"
            placeholder="Cari nama KUBE..."
            class="w-80 rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500">

        {{-- Status --}}
        <select class="rounded-xl border border-gray-300 px-4 py-3">
            <option>Semua Status</option>
        </select>

        {{-- Perkembangan --}}
        <select class="rounded-xl border border-gray-300 px-4 py-3">
            <option>Semua Perkembangan</option>
        </select>

    </div>

    {{-- Kanan --}}
    <div class="flex items-center gap-3">

        <button class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg font-medium">
            PDF
        </button>

        <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-medium">
            Excel
        </button>

    </div>

</div>

    </div>

</div>
<!-- TABLE -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-200 text-gray-600 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama KUBE</th>
                    <th class="px-6 py-3">Periode</th>
                    <th class="px-6 py-3">Omset</th>
                    <th class="px-6 py-3">Pengeluaran</th>
                    <th class="px-6 py-3">Laba Bersih</th>
                    <th class="px-6 py-3">Selisih Laba</th>
                    <th class="px-6 py-3">Perkembangan</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>

                    <td class="px-6 py-4 font-semibold text-gray-800">
                        @php
                            $namaKube = '-';
                            if ($item->laporan && $item->laporan->cluster) {
                                $firstKube = $item->laporan->cluster->kube->first();
                                if ($firstKube) $namaKube = $firstKube->nama_kube;
                            }
                        @endphp
                        {{ $namaKube }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->laporan->periode_bulan ?? '-' }}/{{ $item->laporan->periode_tahun ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        Rp {{ number_format($item->omset_pendapatan ?? 0, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4">
                        Rp {{ number_format($item->total_pengeluaran ?? 0, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="{{ ($item->laba_bersih ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                            Rp {{ number_format($item->laba_bersih ?? 0, 0, ',', '.') }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                            Rp {{ number_format($item->selisih_laba ?? 0, 0, ',', '.') }}
                        </td>


                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs
                            @if($item->perkembangan_usaha == 'Meningkat') bg-green-100 text-green-700
                            @elseif($item->perkembangan_usaha == 'Menurun') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $item->perkembangan_usaha }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $item->status_hasil == 'Tercapai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $item->status_hasil }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openViewModal('{{ $item->id_perkembangan }}')"
                                class="text-green-500 hover:underline text-xs">Lihat</button>
                    </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-6 text-gray-500">Belum ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="flex justify-end mt-4">
    <button onclick="openGrafikModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm shadow">
        Grafik Perkembangan
    </button>
</div>
</form>



<script>
function openTambahModal() {
    document.getElementById('modal-tambah').classList.remove('hidden');
}

function closeTambahModal() {
    document.getElementById('modal-tambah').classList.add('hidden');
}
</script>
            <script>

                
        const formTambah = document.getElementById('form-tambah');

        if(formTambah){
            formTambah.addEventListener('submit', function(e){
                alert('FORM BENAR-BENAR DI SUBMIT');
            });
        }
        </script>
                </form>
            </div>
        </div>

<!-- ==================== MODAL VIEW ==================== -->
<div id="modal-view" tabindex="-1"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-lg rounded-lg p-6 relative">
        <button type="button" onclick="closeViewModal()"
            class="absolute top-2 right-3 text-gray-500 text-xl">X</button>
        <h3 class="text-lg font-bold mb-4">Detail Data Perkembangan Usaha</h3>
        <div id="view-loading" class="text-center py-6 text-gray-400">Memuat data...</div>
        <div id="view-content" class="hidden space-y-3">
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Nama KUBE</span>
                <span class="text-sm font-semibold text-gray-800" id="view-nama-kube">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Periode</span>
                <span class="text-sm font-semibold text-gray-800" id="view-periode">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Omset</span>
                <span class="text-sm font-semibold text-gray-800" id="view-omset">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Total Pengeluaran</span>
                <span class="text-sm font-semibold text-gray-800" id="view-pengeluaran">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Laba Bersih</span>
                <span class="text-sm font-semibold text-gray-800" id="view-laba">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Selisih Laba</span>
                <span class="text-sm font-semibold text-gray-800" id="view-selisih-laba">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Perkembangan</span>
                <span class="text-sm font-semibold px-2 py-1 rounded text-xs" id="view-perkembangan">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Status</span>
                <span class="text-sm font-semibold px-2 py-1 rounded text-xs" id="view-status">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Hasil Evaluasi</span>
                <span class="text-sm text-gray-800 text-right" id="view-evaluasi">-</span>
            </div>
            <div class="flex justify-between border-b pb-2">
                <span class="text-sm text-gray-500 w-40">Rekomendasi</span>
                <span class="text-sm text-gray-800 text-right" id="view-rekomendasi">-</span>
            </div>
            <div class="flex justify-between pb-2">
                <span class="text-sm text-gray-500 w-40">Tanggal Input</span>
                <span class="text-sm text-gray-800" id="view-created-at">-</span>
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" onclick="closeViewModal()" class="px-4 py-2 border rounded text-sm">Tutup</button>
        </div>
    </div>
</div>

<!-- ==================== MODAL EDIT ==================== -->
<div id="modal-edit" tabindex="-1"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-lg rounded-lg p-6 relative max-h-screen overflow-y-auto">
        <button type="button"
            onclick="document.getElementById('modal-edit').classList.add('hidden')"
            class="absolute top-2 right-3 text-gray-500 text-xl">X</button>
        <h3 class="text-lg font-bold mb-4">Edit Data</h3>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
           <div>
    <label class="block text-sm font-medium text-gray-700">
        KUBE
    </label>
    <input
        type="text"
        id="edit_nama_kube"
        class="w-full border rounded-lg bg-gray-100"
        readonly>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">
                Periode
            </label>

            <input
                type="text"
                id="edit_periode"
                class="w-full border rounded-lg bg-gray-100"
                readonly>
        </div>
                
            <label class="text-sm font-medium">Perkembangan</label>
                <input type="text"
                id="edit-perkembangan"
                class="w-full border rounded p-2 bg-gray-100"
                readonly>
            <label class="text-sm font-medium">Status</label>
                <input type="text"
                id="edit-status"
                class="w-full border rounded p-2 bg-gray-100"
                readonly>

            <div class="mb-3">
                <label class="text-sm font-medium">Evaluasi</label>
                <textarea name="hasil_evaluasi" id="edit-evaluasi" class="w-full border rounded p-2" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="text-sm font-medium">Rekomendasi</label>
                <textarea name="rekomendasi" id="edit-rekomendasi" class="w-full border rounded p-2" rows="2"></textarea>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button"
                    onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="px-4 py-2 border rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL GRAFIK ==================== -->
<div id="modal-grafik" tabindex="-1"
    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white w-full max-w-3xl rounded-lg p-6 relative">
        <button type="button" onclick="closeGrafikModal()"
            class="absolute top-2 right-3 text-gray-500 text-xl">X</button>
        <h3 class="text-lg font-bold mb-2">Grafik Perkembangan Omset</h3>

        <div class="mb-4">
            <label class="text-sm font-medium text-gray-600">Filter KUBE:</label>
            <select id="filter-kube-grafik" class="border rounded px-3 py-1 text-sm ml-2">
                <option value="">-- Semua KUBE --</option>
                @foreach ($kubeList as $kube)
                    <option value="{{ $kube['id_cluster'] }}">{{ $kube['nama_kube'] }}</option>
                @endforeach
            </select>
        </div>

        <div id="grafik-loading" class="text-center py-10 text-gray-400">Memuat grafik...</div>
        <canvas id="grafikOmset" class="hidden" height="120"></canvas>
    </div>
</div>

<!-- ==================== SCRIPT ==================== -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

document.getElementById('select-kube').addEventListener('change', function () {
    var idCluster = this.value;
    var selectPeriode = document.getElementById('select-periode');
    selectPeriode.innerHTML = '<option value="">-- Memuat... --</option>';
    if (!idCluster) {
        selectPeriode.innerHTML = '<option value="">-- Pilih KUBE dulu --</option>';
        return;
    }
    fetch('/pendamping/perkembangan-usaha/periode/' + idCluster)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.length === 0) {
                selectPeriode.innerHTML = '<option value="">-- Tidak ada periode --</option>';
                return;
            }
            selectPeriode.innerHTML = '<option value="">-- Pilih Periode --</option>';
            data.forEach(function(item) {
                selectPeriode.innerHTML += '<option value="' + item.id_laporan + '">' + item.periode_bulan + '/' + item.periode_tahun + '</option>';
            });
        })
        .catch(function() {
            selectPeriode.innerHTML = '<option value="">-- Gagal memuat --</option>';
        });
});

function openViewModal(id) {
    document.getElementById('modal-view').classList.remove('hidden');
    document.getElementById('view-loading').classList.remove('hidden');
    document.getElementById('view-content').classList.add('hidden');
    fetch('/admin/perkembangan-usaha/' + id + '/detail')
        .then(function(res) { return res.json(); })
        .then(function(data) {
            document.getElementById('view-loading').classList.add('hidden');
            document.getElementById('view-content').classList.remove('hidden');
            document.getElementById('view-nama-kube').textContent   = data.nama_kube;
            document.getElementById('view-periode').textContent      = data.periode;
            document.getElementById('view-omset').textContent        = 'Rp ' + data.omset;
            document.getElementById('view-pengeluaran').textContent  = 'Rp ' + data.total_pengeluaran;
            document.getElementById('view-laba').textContent         = 'Rp ' + data.laba_bersih;
            document.getElementById('view-selisih-laba').textContent = 'Rp ' + data.selisih_laba;
            document.getElementById('view-evaluasi').textContent     = data.hasil_evaluasi;
            document.getElementById('view-rekomendasi').textContent  = data.rekomendasi;
            document.getElementById('view-created-at').textContent   = data.created_at;
                 var perkembangan = document.getElementById('view-perkembangan');
                     perkembangan.textContent = data.perkembangan_usaha;
                     perkembangan.className = 'text-sm font-semibold px-2 py-1 rounded text-xs ';
                if (data.perkembangan_usaha === 'Meningkat') perkembangan.className += 'bg-green-100 text-green-700';
                else if (data.perkembangan_usaha === 'Menurun') perkembangan.className += 'bg-red-100 text-red-700';
                else perkembangan.className += 'bg-gray-100 text-gray-700';
                var status = document.getElementById('view-status');
                    status.textContent = data.status_hasil;
                    status.className = 'text-sm font-semibold px-2 py-1 rounded text-xs ';
                    status.className += data.status_hasil === 'Tercapai' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
        })
        .catch(function() {
            document.getElementById('view-loading').textContent = 'Gagal memuat data.';
        });
}

function closeViewModal() {
    document.getElementById('modal-view').classList.add('hidden');
}

function openEditModal(id) {
    fetch(`/pendamping/perkembangan-usaha/${id}/edit`)
        .then(res => res.json())
        .then(item => {

            document.getElementById('form-edit').action =
                `/pendamping/perkembangan-usaha/${id}`;

            document.getElementById('edit_nama_kube').value =
                item.nama_kube || '';

            document.getElementById('edit_periode').value =
                item.periode || '';

            document.getElementById('edit-perkembangan').value =
                item.perkembangan_usaha || '';

            document.getElementById('edit-status').value =
                item.status_hasil || '';

            document.getElementById('edit-evaluasi').value =
                item.hasil_evaluasi || '';

            document.getElementById('edit-rekomendasi').value =
                item.rekomendasi || '';

            console.log(document.getElementById('form-edit').action);

            document.getElementById('modal-edit')
                .classList.remove('hidden');
        })
        .catch(error => {
            console.error(error);
            alert('Gagal memuat data edit');
        });
}

document.getElementById('edit-select-kube').addEventListener('change', function () {
    var idCluster = this.value;
    var selectPeriode = document.getElementById('edit-select-periode');
    selectPeriode.innerHTML = '<option value="">-- Memuat... --</option>';
    if (!idCluster) {
        selectPeriode.innerHTML = '<option value="">-- Pilih KUBE dulu --</option>';
        return;
    }
    fetch('/pendamping/perkembangan-usaha/periode/' + idCluster)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            selectPeriode.innerHTML = '<option value="">-- Pilih Periode --</option>';
            data.forEach(function(item) {
                selectPeriode.innerHTML += '<option value="' + item.id_laporan + '">' + item.periode_bulan + '/' + item.periode_tahun + '</option>';
            });
        });
});

// ── GRAFIK ──
var grafikInstance = null;

function openGrafikModal() {
    document.getElementById('modal-grafik').classList.remove('hidden');
    document.getElementById('filter-kube-grafik').value = '';
    loadGrafik('');
}

function loadGrafik(idCluster) {
    document.getElementById('grafik-loading').classList.remove('hidden');
    document.getElementById('grafikOmset').classList.add('hidden');

    var url = '/admin/perkembangan-usaha/grafik';
    if (idCluster) url += '?id_cluster=' + idCluster;

    fetch(url)
        .then(function(res) { return res.json(); })
        .then(function(data) {
            document.getElementById('grafik-loading').classList.add('hidden');
            document.getElementById('grafikOmset').classList.remove('hidden');

            var labels = data.map(function(d) { return d.label; });
            var omsets = data.map(function(d) { return d.omset; });

            if (grafikInstance) grafikInstance.destroy();

            var ctx = document.getElementById('grafikOmset').getContext('2d');
            grafikInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Omset (Rp)',
                        data: omsets,
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.3,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        })
        .catch(function() {
            document.getElementById('grafik-loading').textContent = 'Gagal memuat grafik.';
        });
}

function closeGrafikModal() {
    document.getElementById('modal-grafik').classList.add('hidden');
}

document.getElementById('filter-kube-grafik').addEventListener('change', function() {
    loadGrafik(this.value);
});

</script>

@endsection