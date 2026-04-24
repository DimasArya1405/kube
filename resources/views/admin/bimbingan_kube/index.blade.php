@extends('admin.layout')

@section('breadcrumb')
Bimbingan / <span class="text-gray-800">Data Bimbingan KUBE</span>
@stop

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Bimbingan KUBE oleh Pendamping</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola laporan dan hasil bimbingan kelompok usaha bersama.</p>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <form action="{{ route('bimbingan.index') }}" method="GET" class="flex items-end gap-3 w-full md:w-auto">
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
            </div>
            <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition text-sm font-medium">Filter</button>
            @if(request('from') || request('to'))
                <a href="{{ route('bimbingan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">Reset</a>
            @endif
        </form>

        <div class="flex gap-2 w-full md:w-auto">
            <button onclick="toggleModal('tambahBimbinganModal')" class="flex items-center px-4 py-2 bg-cyan-700 text-white text-sm font-medium rounded-lg hover:bg-cyan-800 transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Data Bimbingan
            </button>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-cyan-700">
                <tr>
                    <th class="py-3 px-5 text-white font-semibold text-sm">No.</th>
                    <th class="py-3 px-5 text-white font-semibold text-sm">Tanggal</th>
                    <th class="py-3 px-5 text-white font-semibold text-sm">KUBE</th>
                    <th class="py-3 px-5 text-white font-semibold text-sm">Pendamping</th>
                    <th class="py-3 px-5 text-white font-semibold text-sm text-center">Jenis Bimbingan</th>
                    <th class="py-3 px-5 text-white font-semibold text-sm text-center">Status</th>
                    <th class="py-3 px-5 text-white font-semibold text-sm text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($datas as $index => $d)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="py-3 px-5 text-gray-800">{{ $index + 1 }}.</td>

                    <td class="py-3 px-5 text-gray-600">
                        {{ $d->tanggal_bimbingan ? \Carbon\Carbon::parse($d->tanggal_bimbingan)->format('d/m/Y') : '-' }}
                    </td>

                    <td class="py-3 px-5 text-cyan-700 font-bold">
                        {{ optional($d->kube)->nama_kube ?? 'N/A' }}
                    </td>

                    <td class="py-3 px-5 text-gray-800">
                        {{ optional($d->pendamping)->nama_pendamping ?? 'N/A' }}
                    </td>

                    <td class="py-3 px-5 text-center">{{ $d->jenis_bimbingan }}</td>

                    <td class="py-3 px-5 text-center">
                        @if($d->status_bimbingan == 'Terlaksana')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">● Terlaksana</span>
                        @elseif($d->status_bimbingan == 'Dijadwalkan')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">● Dijadwalkan</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">● Ditunda</span>
                        @endif
                    </td>

                    <td class="py-3 px-5 text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="{{ route('bimbingan.edit', $d->id_bimbingan) }}" class="text-gray-400 hover:text-cyan-600 transition text-lg">
                                <i class="far fa-edit"></i>
                            </a>

                            <form action="{{ route('bimbingan.destroy', $d->id_bimbingan) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data bimbingan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition text-lg">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-10 text-center text-gray-400">Belum ada data bimbingan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH DATA --}}
<div id="tambahBimbinganModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden">

        <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50">
            <h3 class="text-lg font-bold text-cyan-800">Tambah Data Bimbingan KUBE</h3>
            <button type="button" onclick="toggleModal('tambahBimbinganModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="md:col-span-2">
                    <label>Jadwal Bimbingan</label>
                    <input type="number" name="id_jadwal" class="w-full border rounded px-3 py-2" required>
                </div>

                {{-- ✅ FIX PENDAMPING --}}
                <div>
                    <label>Pendamping</label>
                    <select name="id_pendamping" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih Pendamping --</option>
                        @foreach($pendampings as $p)
                            <option value="{{ $p->id_pendamping }}">
                                {{ $p->nama_pendamping }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>KUBE</label>
                    <select name="id_kube" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih KUBE --</option>
                        @foreach($kubes as $k)
                            <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label>Jenis Bimbingan</label>
                    <select name="jenis_bimbingan" class="w-full border rounded px-3 py-2" required>
                        <option value="Manajemen Usaha">Manajemen Usaha</option>
                        <option value="Pencatatan Keuangan">Pencatatan Keuangan</option>
                        <option value="Strategi Pemasaran">Strategi Pemasaran</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label>Materi</label>
                    <input type="text" name="materi_bimbingan" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label>Tanggal</label>
                    <input type="date" name="tanggal_bimbingan" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="md:col-span-2">
                    <label>Status</label>
                    <select name="status_bimbingan" class="w-full border rounded px-3 py-2">
                        <option value="Terlaksana">Terlaksana</option>
                        <option value="Dijadwalkan">Dijadwalkan</option>
                        <option value="Ditunda">Ditunda</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label>Lampiran</label>
                    <input type="file" name="lampiran">
                </div>

            </div>

            <div class="p-4 border-t flex justify-end gap-2">
                <button type="button" onclick="toggleModal('tambahBimbinganModal')">Batal</button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>

        </form>
    </div>
</div>

<script>
function toggleModal(id){
    document.getElementById(id).classList.toggle('hidden');
}
</script>

@endsection