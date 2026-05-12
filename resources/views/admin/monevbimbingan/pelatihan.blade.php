@extends('admin.layout')

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        position: absolute;
        right: 0;
        top: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        opacity: 0;
        cursor: pointer;
    }

    .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2;
    }

    .icon-large {
        width: 32px;
        height: 32px;
    }
</style>

<div class="flex min-h-screen bg-gray-100">
    <main class="flex-grow px-10 pt-2 pb-10">
        <div class="mb-4 mt-1">
            <h2 class="text-4xl font-bold text-gray-800 tracking-tight">Data Pelatihan</h2>
            <p class="text-gray-500 text-lg">Kelola Data Pelatihan KUBE</p>
        </div>

        <div class="flex justify-between items-center mb-6 gap-6">
            {{-- SEARCH BAR --}}
            <form action="{{ route('pelatihan.index') }}" method="GET" class="relative flex-grow max-w-4xl">
                <input type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari Pelatihan..."
                    class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#2C7A94] focus:outline-none transition-all text-gray-600 bg-white">
                <div class="absolute left-4 top-3.5 text-gray-400">
                    <button type="submit">
                        <i data-lucide="search"></i>
                    </button>
                </div>
            </form>

            {{--TOMBOL EKSPOR & TAMBAH --}}
            <div class="flex gap-3 shrink-0">
                <a href="{{ route('pelatihan.pdf') }}" class="bg-[#F07124] hover:bg-orange-600 text-white px-5 py-2.5 rounded-lg flex items-center text-sm font-bold transition shadow-md">
                    <i data-lucide="file-text" class="mr-2"></i> Ekspor PDF
                </a>

                <a href="{{ route('pelatihan.excel') }}" class="bg-[#21A33F] hover:bg-green-700 text-white px-5 py-2.5 rounded-lg flex items-center text-sm font-bold transition shadow-md">
                    <i data-lucide="file-spreadsheet" class="mr-2"></i> Ekspor Excel
                </a>
                <button onclick="toggleModal(true)" class="bg-[#2C7A94] hover:bg-[#236379] text-white px-5 py-2.5 rounded-lg flex items-center text-sm font-bold transition shadow-md">
                    <i data-lucide="plus" class="mr-2" style="stroke-width: 3;"></i> Tambah Pelatihan
                </button>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-800 uppercase text-[12px] font-bold">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Pelatihan</th>
                        <th class="px-6 py-4">Mitra</th>
                        <th class="px-6 py-4">Pendamping</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Lokasi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($pelatihans as $index => $p)
                    <tr class="hover:bg-gray-50 border-b last:border-0 transition">
                        <td class="px-6 py-4">{{ $index + 1 }}.</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $p->nama_pelatihan }}</td>
                        <td class="px-6 py-4">{{ $p->mitra->nama_mitra ?? '_' }}</td>
                        <td class="px-6 py-4">{{ $p->pendamping->nama_pendamping ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                        <td class="px-6 py-4">{{ $p->lokasi }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                            $statusClasses = [
                            'Terjadwal' => 'bg-green-100 text-green-600',
                            'Selesai' => 'bg-blue-100 text-blue-600',
                            'Dibatalkan' => 'bg-red-100 text-red-600'
                            ][$p->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="{{ $statusClasses }} px-4 py-1.5 rounded-full text-[10px] font-bold uppercase">
                                {{ $p->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-3">
                                {{-- Tombol Detail --}}
                                <button data-item="{{ json_encode($p) }}" onclick="openDetailModal(JSON.parse(this.getAttribute('data-item')))" class="text-blue-600 hover:text-blue-800 transition">
                                    <i data-lucide="eye"></i>
                                </button>
                                {{-- Tombol Edit --}}
                                <button data-item="{{ json_encode($p) }}" onclick="openEditModal(JSON.parse(this.getAttribute('data-item')))" class="text-orange-600 hover:text-orange-800 transition">
                                    <i data-lucide="edit-3"></i>
                                </button>
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('pelatihan.destroy', $p->id_pelatihan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelatihan ini?');" class="inline-block m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-20 text-center text-gray-400 italic bg-gray-50/50"> Belum ada data pelatihan. </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>

{{-- MODAL TAMBAH--}}
<div id="modalTambah" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[9999]">
    <div class="bg-white rounded-[40px] w-[800px] max-h-[95vh] overflow-y-auto p-12 shadow-2xl relative">
        <button onclick="toggleModal(false)" class="absolute top-10 right-10 text-gray-400 hover:text-gray-600">
            <i data-lucide="x" class="icon-large"></i>
        </button>

        <h3 class="text-4xl font-bold text-gray-800 mb-10">Tambah Data Pelatihan</h3>

        <form action="{{ route('pelatihan.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="w-full">
                <label class="block text-base font-bold text-gray-800 mb-2">Nama Pelatihan</label>
                <input type="text" name="nama_pelatihan" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#2C7A94] outline-none" required>
            </div>

            <div>
                <label class="block text-base font-bold text-gray-800 mb-2">Jenis Pelatihan</label>
                <div class="relative">
                    <select name="jenis_pelatihan" class="w-full border border-gray-300 rounded-xl p-3 bg-white outline-none cursor-pointer pr-10" required>
                        <option value="Pertanian">Pertanian</option>
                        <option value="Peternakan">Peternakan</option>
                        <option value="Perikanan">Perikanan</option>
                        <option value="Perdagangan">Perdagangan</option>
                        <option value="Jasa">Jasa</option>
                        <option value="Kerajinan">Kerajinan</option>
                        <option value="Manajemen Keuangan">Manajemen Keuangan</option>
                        <option value="Pemasaran">Pemasaran</option>
                        <option value="Kewirausahaan">Kewirausahaan</option>
                        <option value="Kuliner">Kuliner</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">


                {{-- KUBE (MULTIPLE CHECKBOX) --}}
                <div class="col-span-2">
                    <label class="block text-base font-bold text-gray-800 mb-2">Pilih KUBE Pelatihan <span class="text-sm text-gray-500 font-normal">(Bisa pilih lebih dari satu)</span></label>
                    <div class="w-full border border-gray-300 rounded-xl p-4 bg-white max-h-48 overflow-y-auto">
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($kubes as $k)
                            <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded-lg transition">
                                <input type="checkbox" name="id_kube[]" value="{{ $k->id_kube }}" class="w-5 h-5 text-[#2C7A94] border-gray-300 rounded focus:ring-[#2C7A94]">
                                <span class="text-gray-700 font-medium">{{ $k->nama_kube }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Pendamping</label>
                    <div class="relative">
                        <select name="id_pendamping" class="w-full border border-gray-300 rounded-xl p-3 bg-white outline-none cursor-pointer pr-10">
                            <option value="">Pilih Pendamping</option>
                            @foreach($pendampings as $p) <option value="{{ $p->id_pendamping }}">{{ $p->nama_pendamping }}</option> @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Lokasi Pelatihan</label>
                    <input type="text" name="lokasi" class="w-full border border-gray-300 rounded-xl p-3 outline-none">
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Mitra</label>
                    <div class="relative">
                        <select name="id_mitra" class="w-full border border-gray-300 rounded-xl p-3 bg-white outline-none cursor-pointer pr-10">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitras as $m) <option value="{{ $m->id_mitra }}">{{ $m->nama_mitra }}</option> @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Status</label>
                    <div class="relative">
                        <select name="status" class="w-full border border-gray-300 rounded-xl p-3 bg-white outline-none cursor-pointer pr-10" required>
                            <option value="Terjadwal">Terjadwal</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="chevron-down"></i>
                        </div>
                    </div>
                </div>

                {{-- TANGGAL MULAI--}}
                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Tanggal Mulai</label>
                    <div class="relative">
                        <input type="date" name="tanggal_mulai" class="w-full border border-gray-300 rounded-xl p-3 outline-none bg-white" required>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="calendar"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Tanggal Selesai</label>
                    <div class="relative">
                        <input type="date" name="tanggal_selesai" class="w-full border border-gray-300 rounded-xl p-3 outline-none bg-white">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="calendar"></i>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row-span-2">
                <label class="block text-base font-bold text-gray-800 mb-2">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border border-gray-300 rounded-xl p-3 h-[145px] outline-none resize-none"></textarea>
            </div>

            <div class="flex justify-center gap-6 pt-8">
                <button type="button" onclick="toggleModal(false)" class="bg-[#FF0000] hover:bg-red-700 text-white font-bold py-4 px-20 rounded-2xl transition shadow-lg text-lg"> Batal </button>
                <button type="submit" class="bg-[#2C7A94] hover:bg-[#236379] text-white font-bold py-4 px-20 rounded-2xl transition shadow-lg text-lg"> Simpan </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div id="modalDetail" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[9999]">
    <div class="bg-white rounded-[40px] w-[800px] max-h-[95vh] overflow-y-auto p-12 shadow-2xl relative">
        <button onclick="closeModal('modalDetail')" class="absolute top-10 right-10 text-gray-400 hover:text-gray-600">
            <i data-lucide="x" class="icon-large"></i>
        </button>

        <h3 class="text-4xl font-bold text-gray-800 mb-10">Detail Data Pelatihan</h3>

        <div class="space-y-6">
            <div class="w-full">
                <label class="block text-base font-bold text-gray-800 mb-2">Nama Pelatihan</label>
                <input type="text" id="detail_nama" class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50 outline-none" readonly>
            </div>

            <div>
                <label class="block text-base font-bold text-gray-800 mb-2">Jenis Pelatihan</label>
                <input type="text" id="detail_jenis" class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50 outline-none" readonly>
            </div>

            <div>
                <label class="block text-base font-bold text-gray-800 mb-2">KUBE Peserta</label>
                {{-- Wadah untuk list KUBE berupa badge --}}
                <div id="detail_kube_list" class="w-full border border-gray-300 rounded-xl p-4 bg-gray-50 min-h-[60px] max-h-[120px] overflow-y-auto flex flex-wrap gap-2 content-start">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">


                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Pendamping</label>
                    <input type="text" id="detail_pendamping" class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50 outline-none" readonly>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Lokasi Pelatihan</label>
                    <input type="text" id="detail_lokasi" class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50 outline-none" readonly>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Mitra</label>
                    <input type="text" id="detail_mitra" class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50 outline-none" readonly>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Status</label>
                    <input type="text" id="detail_status" class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50 outline-none" readonly>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Tanggal Mulai</label>
                    <input type="date" id="detail_mulai" class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50 outline-none" readonly>
                </div>



                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Tanggal Selesai</label>
                    <input type="date" id="detail_selesai" class="w-full border border-gray-300 rounded-xl p-3 bg-gray-50 outline-none" readonly>
                </div>




            </div>

            <div class="row-span-2">
                <label class="block text-base font-bold text-gray-800 mb-2">Deskripsi</label>
                <textarea id="detail_deskripsi" class="w-full border border-gray-300 rounded-xl p-3 h-[145px] bg-gray-50 outline-none resize-none" readonly></textarea>
            </div>

            <div class="flex justify-center pt-8">
                <button type="button" onclick="closeModal('modalDetail')" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-20 rounded-2xl transition shadow-lg text-lg"> Tutup </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modalEdit" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[9999]">
    <div class="bg-white rounded-[40px] w-[800px] max-h-[95vh] overflow-y-auto p-12 shadow-2xl relative">
        <button onclick="closeModal('modalEdit')" class="absolute top-10 right-10 text-gray-400 hover:text-gray-600">
            <i data-lucide="x" class="icon-large"></i>
        </button>

        <h3 class="text-4xl font-bold text-gray-800 mb-10">Edit Data Pelatihan</h3>

        <form id="formEdit" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="w-full">
                <label class="block text-base font-bold text-gray-800 mb-2">Nama Pelatihan</label>
                <input type="text" id="edit_nama_pelatihan" name="nama_pelatihan" class="w-full border border-gray-300 rounded-xl p-3 focus:ring-2 focus:ring-[#2C7A94] outline-none" required>
            </div>
            <div>
                <label class="block text-base font-bold text-gray-800 mb-2">Jenis Pelatihan</label>
                <div class="relative">
                    <select id="edit_jenis_pelatihan" name="jenis_pelatihan" class="w-full border border-gray-300 rounded-xl p-3 bg-white outline-none cursor-pointer pr-10" required>
                        <option value="Pertanian">Pertanian</option>
                        <option value="Peternakan">Peternakan</option>
                        <option value="Perikanan">Perikanan</option>
                        <option value="Perdagangan">Perdagangan</option>
                        <option value="Jasa">Jasa</option>
                        <option value="Kerajinan">Kerajinan</option>
                        <option value="Manajemen Keuangan">Manajemen Keuangan</option>
                        <option value="Pemasaran">Pemasaran</option>
                        <option value="Kewirausahaan">Kewirausahaan</option>
                        <option value="Kuliner">Kuliner</option>
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-8">


                {{-- KUBE EDIT (MULTIPLE CHECKBOX) --}}
                <div class="col-span-2">
                    <label class="block text-base font-bold text-gray-800 mb-2">Pilih KUBE Pelatihan</label>
                    <div class="w-full border border-gray-300 rounded-xl p-4 bg-white max-h-48 overflow-y-auto" id="edit_kube_wrapper">
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($kubes as $k)
                            <label class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-gray-50 rounded-lg transition">
                                {{-- Kita tambahkan class khusus "edit-kube-checkbox" untuk mempermudah JS --}}
                                <input type="checkbox" name="id_kube[]" value="{{ $k->id_kube }}" class="edit-kube-checkbox w-5 h-5 text-[#2C7A94] border-gray-300 rounded focus:ring-[#2C7A94]">
                                <span class="text-gray-700 font-medium">{{ $k->nama_kube }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Pendamping</label>
                    <div class="relative">
                        <select id="edit_id_pendamping" name="id_pendamping" class="w-full border border-gray-300 rounded-xl p-3 bg-white outline-none cursor-pointer pr-10">
                            <option value="">Pilih Pendamping</option>
                            @foreach($pendampings as $p) <option value="{{ $p->id_pendamping }}">{{ $p->nama_pendamping }}</option> @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Lokasi Pelatihan</label>
                    <input type="text" id="edit_lokasi" name="lokasi" class="w-full border border-gray-300 rounded-xl p-3 outline-none">
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Mitra</label>
                    <div class="relative">
                        <select id="edit_id_mitra" name="id_mitra" class="w-full border border-gray-300 rounded-xl p-3 bg-white outline-none cursor-pointer pr-10">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitras as $m) <option value="{{ $m->id_mitra }}">{{ $m->nama_mitra }}</option> @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Status</label>
                    <div class="relative">
                        <select id="edit_status" name="status" class="w-full border border-gray-300 rounded-xl p-3 bg-white outline-none cursor-pointer pr-10" required>
                            <option value="Terjadwal">Terjadwal</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Tanggal Mulai</label>
                    <div class="relative">
                        <input type="date" id="edit_tanggal_mulai" name="tanggal_mulai" class="w-full border border-gray-300 rounded-xl p-3 outline-none bg-white" required>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="calendar"></i>
                        </div>
                    </div>
                </div>



                <div>
                    <label class="block text-base font-bold text-gray-800 mb-2">Tanggal Selesai</label>
                    <div class="relative">
                        <input type="date" id="edit_tanggal_selesai" name="tanggal_selesai" class="w-full border border-gray-300 rounded-xl p-3 outline-none bg-white">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                            <i data-lucide="calendar"></i>
                        </div>
                    </div>
                </div>




            </div>

            <div class="row-span-2">
                <label class="block text-base font-bold text-gray-800 mb-2">Deskripsi</label>
                <textarea id="edit_deskripsi" name="deskripsi" class="w-full border border-gray-300 rounded-xl p-3 h-[145px] outline-none resize-none"></textarea>
            </div>

            <div class="flex justify-center gap-6 pt-8">
                <button type="button" onclick="closeModal('modalEdit')" class="bg-[#FF0000] hover:bg-red-700 text-white font-bold py-4 px-20 rounded-2xl transition shadow-lg text-lg"> Batal </button>
                <button type="submit" class="bg-[#2C7A94] hover:bg-[#236379] text-white font-bold py-4 px-20 rounded-2xl transition shadow-lg text-lg"> Update </button>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
    // Fungsi memunculkan modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    // Fungsi menutup modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    // Fungsi tombol struktur yang baru
    function toggleModal(show) {
        if (show) openModal('modalTambah');
        else closeModal('modalTambah');
    }

    // Pop-up Detail
    // Pop-up Detail
// Pop-up Detail
    function openDetailModal(data) {
        let namaPendamping = data.pendamping ? data.pendamping.nama_pendamping : '-';
        let namaMitra = data.mitra ? data.mitra.nama_mitra : '-';

        document.getElementById('detail_nama').value = data.nama_pelatihan || '-';
        document.getElementById('detail_jenis').value = data.jenis_pelatihan || '-';
        
        // --- LOGIKA BARU UNTUK LIST KUBE ---
        let kubeContainer = document.getElementById('detail_kube_list');
        kubeContainer.innerHTML = ''; // Kosongkan wadah setiap kali modal dibuka

        if (data.kubes && data.kubes.length > 0) {
            // Looping dan buat elemen badge untuk tiap KUBE
            data.kubes.forEach(k => {
                let badge = `<span class="bg-[#e6f4f1] text-[#2C7A94] px-3 py-1.5 rounded-lg text-sm font-semibold border border-[#bce0d9] shadow-sm">
                                ${k.nama_kube}
                             </span>`;
                kubeContainer.insertAdjacentHTML('beforeend', badge);
            });
        } else {
            // Kalau nggak ada KUBE yang dipilih
            kubeContainer.innerHTML = '<span class="text-gray-400 italic text-sm py-1">- Tidak ada KUBE peserta -</span>';
        }
        // -----------------------------------

        document.getElementById('detail_pendamping').value = namaPendamping;
        document.getElementById('detail_lokasi').value = data.lokasi || '-';
        document.getElementById('detail_mulai').value = data.tanggal_mulai || '';
        document.getElementById('detail_status').value = data.status || '-';
        document.getElementById('detail_selesai').value = data.tanggal_selesai || '';
        document.getElementById('detail_deskripsi').value = data.deskripsi || '-';
        document.getElementById('detail_mitra').value = namaMitra;

        openModal('modalDetail');
    }

    // Pop-up Edit
    function openEditModal(data) {
        const form = document.getElementById('formEdit');
        form.action = `/pelatihan/${data.id_pelatihan}`;

        document.getElementById('edit_nama_pelatihan').value = data.nama_pelatihan || '';
        document.getElementById('edit_jenis_pelatihan').value = data.jenis_pelatihan || '';
        document.getElementById('edit_id_pendamping').value = data.id_pendamping || '';
        document.getElementById('edit_lokasi').value = data.lokasi || '';
        document.getElementById('edit_tanggal_mulai').value = data.tanggal_mulai || '';
        document.getElementById('edit_status').value = data.status || '';
        document.getElementById('edit_tanggal_selesai').value = data.tanggal_selesai || '';
        document.getElementById('edit_deskripsi').value = data.deskripsi || '';
        document.getElementById('edit_id_mitra').value = data.id_mitra || '';

        // --- BAGIAN PENTING UNTUK CHECKBOX KUBE ---
        // 1. Reset semua centang checkbox terlebih dahulu
        let checkboxes = document.querySelectorAll('.edit-kube-checkbox');
        checkboxes.forEach(cb => cb.checked = false);

        // 2. Ambil array ID KUBE yang dimiliki oleh pelatihan ini
        if (data.kubes && data.kubes.length > 0) {
            let selectedKubeIds = data.kubes.map(k => k.id_kube.toString());

            // 3. Centang kembali checkbox yang ID-nya cocok
            checkboxes.forEach(cb => {
                if (selectedKubeIds.includes(cb.value)) {
                    cb.checked = true;
                }
            });
        }

        openModal('modalEdit');
    }
</script>
@endsection