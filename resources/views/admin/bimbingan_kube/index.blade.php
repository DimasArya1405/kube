@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@extends('admin.layout')

@section('breadcrumb')
Bimbingan / <span class="text-gray-800">Data Bimbingan KUBE</span>
@stop

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Bimbingan KUBE oleh Pendamping</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola laporan dan hasil bimbingan kelompok usaha bersama.</p>
    </div>

    {{-- FILTER + BUTTON --}}
<div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4 mb-6">

    {{-- FILTER --}}
    <form action="{{ route('bimbingan.index') }}" method="GET"
        class="flex flex-wrap items-end gap-3">

        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase">
                Dari Tanggal
            </label>

            <input type="date" name="from" value="{{ request('from') }}"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
        </div>

        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase">
                Sampai Tanggal
            </label>

            <input type="date" name="to" value="{{ request('to') }}"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50">
        </div>

        <button type="submit"
            class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 text-sm font-medium">
            Filter
        </button>

        @if(request('from') || request('to'))
            <a href="{{ route('bimbingan.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium">
                Reset
            </a>
        @endif

    </form>

    {{-- BUTTON GROUP --}}
    <div class="flex gap-2">

        {{-- EXPORT PDF --}}
        <a href="{{ route('bimbingan.pdf', [
            'from' => request('from'),
            'to' => request('to')
        ]) }}"
        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium shadow-sm">
            Export PDF
        </a>

        {{-- TAMBAH DATA --}}
        <button onclick="toggleModal('tambahBimbinganModal')"
            class="px-4 py-2 bg-cyan-700 text-white rounded-lg hover:bg-cyan-800 text-sm font-medium shadow-sm">
            + Tambah Data
        </button>

    </div>

</div>

</div>

    {{-- TABLE --}}
    <div class="bg-white shadow-sm rounded-xl border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-cyan-700 text-white">
                <tr>
                    <th class="py-3 px-5">No</th>
                    <th class="py-3 px-5">Tanggal</th>
                    <th class="py-3 px-5">KUBE</th>
                    <th class="py-3 px-5">Pendamping</th>
                    <th class="py-3 px-5 text-center">Jenis</th>
                    <th class="py-3 px-5 text-center">Status</th>
                    <th class="py-3 px-5 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($datas as $index => $d)
                <tr class="border-b hover:bg-gray-50">

                    <td class="px-5 py-3">{{ $index + 1 }}</td>

                    <td class="px-5 py-3">
                        {{ $d->tanggal_bimbingan ? \Carbon\Carbon::parse($d->tanggal_bimbingan)->format('d/m/Y') : '-' }}
                    </td>

                    <td class="px-5 py-3 font-semibold text-cyan-700">
                        {{ optional($d->kube)->nama_kube ?? '-' }}
                    </td>

                    <td class="px-5 py-3">
                        {{ $d->id_pendamping == 1 ? 'Siti Aryani' : '-' }}
                    </td>

                    <td class="px-5 py-3 text-center">
                        {{ $d->jenis_bimbingan }}
                    </td>

                    <td class="px-5 py-3 text-center">
                        @if($d->status_bimbingan == 'Terlaksana')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">Terlaksana</span>
                        @elseif($d->status_bimbingan == 'Dijadwalkan')
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">Dijadwalkan</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">Ditunda</span>
                        @endif
                    </td>

                    <td class="px-5 py-3 text-center">
    <div class="flex justify-center gap-3 items-center">

        {{-- ICON EDIT --}}
        <a href="{{ route('bimbingan.edit', $d->id_bimbingan) }}"
            class="text-blue-500 hover:text-blue-700">
            <i data-lucide="pencil" class="w-5 h-5"></i>
        </a>

        {{-- ICON HAPUS --}}
        <form action="{{ route('bimbingan.destroy', $d->id_bimbingan) }}" method="POST"
            onsubmit="return confirm('Hapus data?')">
            @csrf
            @method('DELETE')
            <button class="text-red-500 hover:text-red-700">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </button>
        </form>

    </div>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-400">
                        Belum ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- MODAL TAMBAH --}}
<div id="tambahBimbinganModal"
    class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 overflow-y-auto flex items-start justify-center pt-10">

    <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto">

        {{-- HEADER --}}
        <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50 sticky top-0 z-10">
            <h3 class="text-lg font-bold text-cyan-800">Tambah Data Bimbingan KUBE</h3>
            <button type="button" onclick="toggleModal('tambahBimbinganModal')"
                class="text-gray-500 hover:text-red-500 text-lg">✕</button>
        </div>

        {{-- FORM --}}
        <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- JADWAL --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Jadwal</label>
                    <input type="number" name="id_jadwal"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500"
                        required>
                </div>

                {{-- AUTO PENDAMPING --}}
                <div>
    <label class="text-sm font-medium text-gray-700">Pendamping</label>

    <input type="text" value="Siti Aryani"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100"
        readonly>

    <input type="hidden" name="id_pendamping" value="1">
</div>

                {{-- KUBE --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">KUBE</label>
                    <select name="id_kube"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500"
                        required>
                        <option value="">-- Pilih KUBE --</option>
                        @foreach($kubes as $k)
                            <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- JENIS --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Jenis Bimbingan</label>
                    <select name="jenis_bimbingan"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500"
                        required>
                        <option value="Manajemen Usaha">Manajemen Usaha</option>
                        <option value="Pencatatan Keuangan">Pencatatan Keuangan</option>
                        <option value="Strategi Pemasaran">Strategi Pemasaran</option>
                        <option value="Motivasi">Motivasi</option>
                        <option value="Mediasi">Mediasi</option>
                    </select>
                </div>

                {{-- MATERI --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Materi</label>
                    <input type="text" name="materi_bimbingan"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                </div>

                {{-- TANGGAL --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" name="tanggal_bimbingan"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500"
                        required>
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="text-sm font-medium text-gray-700">Status</label>
                    <select name="status_bimbingan"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        <option value="Terlaksana">Terlaksana</option>
                        <option value="Dijadwalkan">Dijadwalkan</option>
                        <option value="Ditunda">Ditunda</option>
                    </select>
                </div>

                {{-- HASIL --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Hasil Bimbingan</label>
                    <textarea name="hasil_bimbingan" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500"></textarea>
                </div>

                {{-- TINDAK LANJUT --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Tindak Lanjut</label>
                    <textarea name="tindak_lanjut" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500"></textarea>
                </div>

                {{-- LAMPIRAN --}}
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-700">Lampiran</label>
                    <input type="file" name="lampiran"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                </div>

            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 sticky bottom-0">
                <button type="button"
                    onclick="toggleModal('tambahBimbinganModal')"
                    class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                    Batal
                </button>

                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Simpan
                </button>
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