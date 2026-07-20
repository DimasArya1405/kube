@extends('admin.layout')

@section('title', 'Data Pengajuan Bantuan Baru')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Pengajuan Bantuan Baru</span>
@stop

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Pengajuan Bantuan Baru</h2>
            <p class="text-gray-500 text-sm">Kelola pengajuan bantuan dengan ID berbeda untuk setiap jenis bantuan.</p>
        </div>

        <a href="{{ route('admin.pengajuan_bantuan_baru.create') }}"
            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow">
            + Tambah Pengajuan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-3 text-left">ID</th>
                    <th class="p-3 text-left">Nama KUBE</th>
                    <th class="p-3 text-left">Jenis Bantuan</th>
                    <th class="p-3 text-left">Jumlah</th>
                    <th class="p-3 text-left">Tanggal</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($data as $d)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 font-mono text-gray-700">
                        {{ $d->id_pengajuan_kube }}
                    </td>
                    <td class="p-3 font-medium text-gray-800">
                        {{ $d->kube->nama_kube ?? '-' }}
                    </td>
                    <td class="p-3">
                        {{ $d->jenisBantuan->jenis_bantuan ?? '-' }}
                    </td>
                    <td class="p-3">
                        {{ number_format($d->jumlah_bantuan, 0, ',', '.') }}
                    </td>
                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($d->tanggal_pengajuan)->format('d-m-Y') }}
                    </td>
                    <td class="p-3">
                        @if($d->status_pengajuan == 'diajukan')
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Diajukan</span>
                        @elseif($d->status_pengajuan == 'disetujui')
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Disetujui</span>
                        @elseif($d->status_pengajuan == 'cair')
                            <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-700 rounded">Cair</span>
                        @else
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Ditolak</span>
                        @endif
                    </td>
                    <td class="p-3 text-center">
                        <button onclick="toggleDetail({{ $d->id_pengajuan_kube }})"
                            class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                            Detail
                        </button>
                    </td>
                </tr>
                <tr id="detail-{{ $d->id_pengajuan_kube }}" class="hidden bg-gray-50">
                    <td colspan="7" class="p-4">
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
                                <tr>
                                    <td class="p-2">{{ $item->jenisBantuan->jenis_bantuan ?? '-' }}</td>
                                    <td class="p-2">{{ $item->nama_item }}</td>
                                    <td class="p-2">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center p-2 text-gray-400">Tidak ada detail</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500">
                        Belum ada data.
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
