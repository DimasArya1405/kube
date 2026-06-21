@extends('admin.layout')

@section('breadcrumb')
    Data Master / <span class="text-gray-800">Data Pelatihan</span>
@stop

@section('content')
<script src="https://unpkg.com/lucide@latest"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<style>
    /* Penyesuaian Style Dasar */
    .lucide {
        width: 18px;
        height: 18px;
        stroke-width: 2;
    }
    
    /* Kustomisasi TomSelect agar selaras dengan Tailwind Target DNA */
    .ts-control {
        border-radius: 0.5rem !important; /* rounded-lg */
        border-color: #d1d5db !important; /* border-gray-300 */
        padding: 0.5rem 1rem !important; /* py-2 px-4 */
        font-size: 0.875rem !important; /* text-sm */
        min-height: 42px !important;
    }
    .ts-control.focus {
        border-color: #3b82f6 !important; /* border-blue-500 */
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important; /* ring-blue-500 */
    }
</style>

{{-- HEADER --}}
<div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Data Pelatihan</h2>
        <p class="text-gray-500 mt-1">Kelola data pelatihan, mitra, lokasi, dan KUBE peserta.</p>
    </div>
    <div>
        <button type="button" onclick="toggleModal('modalTambah')"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition flex items-center shadow-sm">
            <i data-lucide="plus" class="mr-2" style="stroke-width: 3;"></i> Tambah Pelatihan
        </button>
    </div>
</div>

{{-- ALERT MESSAGES (Sesuai DNA) --}}
@if(session('success'))
<div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg relative mb-4 shadow-sm" role="alert">
    <span class="block sm:inline font-medium">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow-sm" role="alert">
    <strong class="font-bold">Oops!</strong>
    <span class="block sm:inline font-medium">{{ session('error') }}</span>
</div>
@endif

{{-- FILTER & SEARCH AREA --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border p-4">
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4">
        <form action="{{ route('pelatihan.index') }}" method="GET" class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i data-lucide="search" class="w-5 h-5"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-sm placeholder:text-gray-400" placeholder="Cari nama pelatihan...">
            <button type="submit" class="hidden"></button>
        </form>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('pelatihan.pdf') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm transition shadow-sm flex items-center font-bold">
                <i data-lucide="file-text" class="mr-2 w-5 h-5"></i> Export PDF
            </a>
            <a href="{{ route('pelatihan.excel') }}" class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 text-sm transition shadow-sm flex items-center font-bold">
                <i data-lucide="file-spreadsheet" class="mr-2 w-5 h-5"></i> Export Excel
            </a>
        </div>
    </div>
</div>

{{-- TABEL UTAMA --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-center">No</th>
                    <th class="px-6 py-3">Nama Pelatihan</th>
                    <th class="px-6 py-3">Mitra</th>
                    <th class="px-6 py-3">Pendamping</th>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Lokasi</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelatihans as $index => $p)
                <tr class="border-b bg-white hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $p->nama_pelatihan }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $p->mitra->nama_mitra ?? '-' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $p->pendamping->nama_pendamping ?? '-' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $p->lokasi }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                        @if($p->status == 'Terjadwal')
                            <span class="bg-amber-100 border border-amber-200 px-2 py-1 text-xs rounded-md text-amber-700 font-semibold">Terjadwal</span>
                        @elseif($p->status == 'Selesai')
                            <span class="bg-emerald-100 border border-emerald-200 px-2 py-1 text-xs rounded-md text-emerald-700 font-semibold">Selesai</span>
                        @else
                            <span class="bg-red-100 border border-red-200 px-2 py-1 text-xs rounded-md text-red-700 font-semibold">Dibatalkan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Button Detail --}}
                            <button type="button" data-item="{{ json_encode($p) }}" onclick="openDetailModal(JSON.parse(this.getAttribute('data-item')))" class="w-9 h-9 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                <i data-lucide="eye"></i>
                            </button>
                            
                            {{-- Button Edit --}}
                            <button type="button" data-item="{{ json_encode($p) }}" onclick="openEditModal(JSON.parse(this.getAttribute('data-item')))" class="w-9 h-9 flex items-center justify-center rounded-lg text-yellow-500 hover:bg-yellow-50 transition-colors" title="Ubah Data">
                                <i data-lucide="edit-3"></i>
                            </button>

                            {{-- Button Delete --}}
                            <form action="{{ route('pelatihan.destroy', $p->id_pelatihan) }}" method="POST" class="inline-block m-0" id="deleteForm-{{ $p->id_pelatihan }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, '{{ $p->id_pelatihan }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Hapus Data">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-10 text-gray-500 italic">
                        Belum ada data pelatihan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL TAMBAH PELATIHAN ================= --}}
<div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('modalTambah')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Data Pelatihan</h3>
            <button type="button" onclick="toggleModal('modalTambah')" class="text-gray-400 hover:text-gray-600 transition">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <form action="{{ route('pelatihan.store') }}" method="POST" class="flex flex-col overflow-hidden flex-1">
            @csrf
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelatihan</label>
                        <input type="text" name="nama_pelatihan" placeholder="Masukkan nama pelatihan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelatihan</label>
                        <select name="jenis_pelatihan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
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
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="Terjadwal">Terjadwal</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih KUBE Pelatihan <span class="text-xs text-gray-400 font-normal">(Bisa pilih banyak)</span></label>
                        <select id="select_kube_tambah" name="id_kube[]" multiple placeholder="Ketik nama KUBE..." autocomplete="off" class="w-full text-sm" required>
                            @foreach($kubes as $k)
                                <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendamping</label>
                        <select name="id_pendamping" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Pilih Pendamping</option>
                            @foreach($pendampings as $p) <option value="{{ $p->id_pendamping }}">{{ $p->nama_pendamping }}</option> @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mitra</label>
                        <select name="id_mitra" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitras as $m) <option value="{{ $m->id_mitra }}">{{ $m->nama_mitra }}</option> @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pelatihan</label>
                        <input type="text" name="lokasi" placeholder="Lokasi kegiatan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Tambahkan keterangan..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modalTambah')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm"> Batal </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm"> Simpan Data </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= MODAL DETAIL PELATIHAN ================= --}}
<div id="modalDetail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('modalDetail')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Detail Pelatihan</h3>
            <button type="button" onclick="toggleModal('modalDetail')" class="text-gray-400 hover:text-gray-600 transition">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <div class="p-6 overflow-y-auto flex-1">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelatihan</label>
                        <input type="text" id="detail_nama" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelatihan</label>
                        <input type="text" id="detail_jenis" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <input type="text" id="detail_status" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm cursor-not-allowed" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            KUBE Peserta <span id="detail_kube_count" class="text-xs bg-blue-100 border border-blue-200 text-blue-700 px-2 py-0.5 rounded-full ml-1 font-semibold">0</span>
                        </label>
                        <div id="detail_kube_list" class="w-full border border-gray-200 rounded-lg p-3 bg-gray-50 max-h-32 overflow-y-auto flex flex-wrap gap-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendamping</label>
                        <input type="text" id="detail_pendamping" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mitra</label>
                        <input type="text" id="detail_mitra" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" id="detail_mulai" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" id="detail_selesai" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm cursor-not-allowed" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pelatihan</label>
                        <input type="text" id="detail_lokasi" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm cursor-not-allowed" readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="detail_deskripsi" rows="3" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-gray-600 text-sm resize-none cursor-not-allowed" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="button" onclick="toggleModal('modalDetail')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm"> Tutup </button>
        </div>
    </div>
</div>

{{-- ================= MODAL EDIT PELATIHAN ================= --}}
<div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('modalEdit')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Edit Data Pelatihan</h3>
            <button type="button" onclick="toggleModal('modalEdit')" class="text-gray-400 hover:text-gray-600 transition">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <form id="formEdit" method="POST" class="flex flex-col overflow-hidden flex-1">
            @csrf
            @method('PUT')
            
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pelatihan</label>
                        <input type="text" id="edit_nama_pelatihan" name="nama_pelatihan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pelatihan</label>
                        <select id="edit_jenis_pelatihan" name="jenis_pelatihan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
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
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="edit_status" name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="Terjadwal">Terjadwal</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih KUBE Pelatihan</label>
                        <select id="select_kube_edit" name="id_kube[]" multiple placeholder="Ketik nama KUBE..." autocomplete="off" class="w-full text-sm" required>
                            @foreach($kubes as $k)
                                <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendamping</label>
                        <select id="edit_id_pendamping" name="id_pendamping" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Pilih Pendamping</option>
                            @foreach($pendampings as $p) <option value="{{ $p->id_pendamping }}">{{ $p->nama_pendamping }}</option> @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mitra</label>
                        <select id="edit_id_mitra" name="id_mitra" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="">Pilih Mitra</option>
                            @foreach($mitras as $m) <option value="{{ $m->id_mitra }}">{{ $m->nama_mitra }}</option> @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" id="edit_tanggal_mulai" name="tanggal_mulai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" id="edit_tanggal_selesai" name="tanggal_selesai" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pelatihan</label>
                        <input type="text" id="edit_lokasi" name="lokasi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea id="edit_deskripsi" name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"></textarea>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('modalEdit')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm"> Batal </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm"> Simpan Perubahan </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    lucide.createIcons();

    // Inisiasi TomSelect
    const tsTambah = new TomSelect('#select_kube_tambah', {
        plugins: ['remove_button'],
        maxOptions: 200
    });

    const tsEdit = new TomSelect('#select_kube_edit', {
        plugins: ['remove_button'],
        maxOptions: 200
    });

    // Fungsi seragam toggle Modal (DNA KUBE)
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    // Fungsi SweetAlert Konfirmasi Hapus
    function confirmDelete(event, id) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data Pelatihan ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: '#9ca3af',  
            confirmButtonText: 'Ya, Hapus Data!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-4 py-2 font-bold',
                cancelButton: 'rounded-xl px-4 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + id).submit();
            }
        });
    }

    // Modal Detail Logic
    function openDetailModal(data) {
        let namaPendamping = data.pendamping ? data.pendamping.nama_pendamping : '-';
        let namaMitra = data.mitra ? data.mitra.nama_mitra : '-';

        document.getElementById('detail_nama').value = data.nama_pelatihan || '-';
        document.getElementById('detail_jenis').value = data.jenis_pelatihan || '-';
        
        // Logika Badge List KUBE (Desain disesuaikan ke palet biru DNA KUBE)
        let kubeContainer = document.getElementById('detail_kube_list');
        let kubeCountBadge = document.getElementById('detail_kube_count');
        kubeContainer.innerHTML = '';

        if (data.kubes && data.kubes.length > 0) {
            kubeCountBadge.innerText = data.kubes.length;
            kubeCountBadge.classList.remove('hidden');

            data.kubes.forEach(k => {
                let badge = `<span class="bg-white text-gray-700 px-3 py-1 rounded-md text-xs font-semibold border border-gray-200 shadow-sm">
                                ${k.nama_kube}
                             </span>`;
                kubeContainer.insertAdjacentHTML('beforeend', badge);
            });
        } else {
            kubeCountBadge.innerText = '0';
            kubeContainer.innerHTML = '<span class="text-gray-400 italic text-sm py-1">- Tidak ada KUBE peserta -</span>';
        }

        document.getElementById('detail_pendamping').value = namaPendamping;
        document.getElementById('detail_lokasi').value = data.lokasi || '-';
        document.getElementById('detail_mulai').value = data.tanggal_mulai || '';
        document.getElementById('detail_status').value = data.status || '-';
        document.getElementById('detail_selesai').value = data.tanggal_selesai || '';
        document.getElementById('detail_deskripsi').value = data.deskripsi || '-';
        document.getElementById('detail_mitra').value = namaMitra;

        toggleModal('modalDetail');
    }

    // Modal Edit Logic
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

        // Reset TomSelect lalu set ID yang terpilih
        tsEdit.clear();
        if (data.kubes && data.kubes.length > 0) {
            let selectedKubeIds = data.kubes.map(k => k.id_kube.toString());
            tsEdit.setValue(selectedKubeIds);
        }

        toggleModal('modalEdit');
    }
</script>
@endpush
@endsection