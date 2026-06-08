@extends('admin.layout')

@section('title', 'Data Pengajuan KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Pengajuan KUBE</span>
@stop

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Pengajuan KUBE</h2>
            <p class="text-gray-500 text-sm">Monitoring pengajuan bantuan KUBE</p>
        </div>

        <a href="{{ route('pengajuan.create') }}"
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow">
            + Tambah Pengajuan
        </a>
    </div>

    {{-- NOTIF --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-3 text-left">Nama KUBE</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($data as $d)

                {{-- ROW UTAMA --}}
                <tr class="hover:bg-gray-50 transition">

                    <td class="p-3 font-medium text-gray-800">
                        {{ $d->kube->nama_kube ?? '-' }}
                    </td>

                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($d->tanggal_pengajuan)->format('d-m-Y') }}
                    </td>

                    <td class="p-3">
                        @if($d->status_pengajuan == 'diajukan')
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">
                                Diajukan
                            </span>
                        @elseif($d->status_pengajuan == 'disetujui')
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">
                                Disetujui
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">
                                Ditolak
                            </span>
                        @endif
                    </td>

                    <td class="p-3 text-center">
                        <button onclick="toggleDetail({{ $d->id_pengajuan_kube }})"
                            class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                            Detail
                        </button>
                    </td>

                </tr>

                {{-- ROW DETAIL --}}
                <tr id="detail-{{ $d->id_pengajuan_kube }}" class="hidden bg-gray-50">
                    <td colspan="4" class="p-4">

                        <table class="w-full border text-sm">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="p-2">Jenis Bantuan</th>
                                    <th class="p-2">Nama Item</th>
                                    <th class="p-2">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>

                            @forelse($d->detail as $item)

                                @php
                                    $jenis = $item->jenisBantuan->jenis_bantuan ?? '';

                                    $jumlah = match($jenis) {
                                        'Modal Usaha' => 'Rp ' . number_format($item->jumlah, 0, ',', '.'),
                                        'Alat' => number_format($item->jumlah, 0, ',', '.') . ' Unit',
                                        'Pelatihan' => number_format($item->jumlah, 0, ',', '.') . ' Peserta',
                                        default => number_format($item->jumlah, 0, ',', '.')
                                    };
                                @endphp

                                <tr>
                                    <td class="p-2">
                                        {{ $jenis }}
                                    </td>

                                    <td class="p-2">
                                        {{ $item->nama_item }}
                                    </td>

                                    <td class="p-2">
                                        {{ $jumlah }}
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="3" class="text-center p-2 text-gray-400">
                                        Tidak ada detail
                                    </td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>

                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">
                        Belum ada data 😢
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

    </div>

</div>

@endsection

@push('scripts')
<script>
function toggleDetail(id) {
    const el = document.getElementById('detail-' + id);
    el.classList.toggle('hidden');
}
</script>
@endpush