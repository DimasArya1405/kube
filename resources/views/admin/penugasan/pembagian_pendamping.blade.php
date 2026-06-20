@extends('admin.layout')

@section('breadcrumb')
    Penugasan / <span class="text-gray-800">Data Pembagian Pendamping</span>
@stop

@section('content')
{{-- Tambahkan library CSS & JS Tom Select di sini --}}
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

{{-- Custom Style agar Tom Select senada dengan Tailwind Input --}}
<style>
    .ts-control {
        border-radius: 0.75rem !important; /* rounded-xl */
        padding: 0.75rem !important; /* p-3 */
        border-color: #e5e7eb !important; /* border-gray-200 */
        box-shadow: none !important;
        transition: all 0.3s ease;
    }
    .ts-control.focus {
        border-color: #3b82f6 !important; /* border-blue-500 */
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important; /* focus:ring-4 */
    }
</style>

{{-- HEADER --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Pembagian Pendamping</h2>
        <p class="text-gray-500 mt-1">Kelola data pembagian pendamping untuk setiap Kelompok Usaha Bersama.</p>
    </div>
    <div>
        <button type="button" onclick="toggleModal('tambahPembagianModal')"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition flex items-center shadow-sm">
            <!-- <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg> -->
            Tambah Pembagian
        </button>
    </div>
</div>

{{-- ALERT MESSAGES --}}
@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl relative mb-6 shadow-sm" role="alert">
    <strong class="font-bold flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        Oops! Ada kesalahan input:
    </strong>
    <ul class="list-disc pl-9 mt-2 text-sm font-medium">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

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
        <div class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" class="w-full pl-10 pr-4 py-2.5 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-sm placeholder:text-gray-400" placeholder="Cari pembagian...">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('pembagian_pendamping.export.pdf') }}" target="_blank" class="px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl hover:bg-red-100 text-sm transition shadow-sm flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> 
                Export PDF
            </a>
            <a href="{{ route('pembagian_pendamping.export.excel') }}" class="px-4 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-xl hover:bg-emerald-100 text-sm transition shadow-sm flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
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
                    <th class="px-6 py-3 text-center">Nama KUBE</th>
                    <th class="px-6 py-3 text-center">Nama Pendamping</th>
                    <th class="px-6 py-3 text-center">Tgl Mulai</th>
                    <th class="px-6 py-3 text-center">Tgl Selesai</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembagians as $index => $p)
                <tr class="border-b bg-white hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-center font-medium text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 text-center font-bold text-gray-900">{{ $p->kube->nama_kube ?? 'KUBE Dihapus' }}</td>
                    <td class="px-6 py-4 text-center font-medium text-gray-800">{{ $p->pendamping->nama_pendamping ?? 'Pendamping Dihapus' }}</td>
                    <td class="px-6 py-4 text-center font-medium text-gray-600">{{ $p->tgl_pembagian ? \Carbon\Carbon::parse($p->tgl_pembagian)->format('d M Y') : '-' }}</td>
                    <td class="px-6 py-4 text-center font-medium text-gray-600">{{ $p->tgl_selesai ? \Carbon\Carbon::parse($p->tgl_selesai)->format('d M Y') : '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        @if(strtolower($p->status) == 'aktif')
                            <span class="bg-blue-200 px-3 py-1 text-xs rounded-md text-blue-800 font-bold">Aktif</span>
                        @else
                            <span class="bg-emerald-200 px-3 py-1 text-xs rounded-md text-emerald-800 font-bold">Selesai</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Tombol Tandai Selesai --}}
                            @if(strtolower($p->status) == 'aktif')
                            <form action="{{ route('pembagian_pendamping.selesai', $p->id_pembagian) }}" method="POST" class="inline-block m-0" id="selesaiForm-{{ $p->id_pembagian }}">
                                @csrf
                                @method('PATCH')
                                <button type="button" onclick="confirmSelesai(event, '{{ $p->id_pembagian }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-emerald-500 hover:bg-emerald-50 transition-colors" title="Tandai Selesai">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            </form>
                            @endif

                            {{-- Tombol Delete --}}
                            <form action="{{ route('pembagian_pendamping.destroy', $p->id_pembagian) }}" method="POST" class="inline-block m-0" id="deleteForm-{{ $p->id_pembagian }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, '{{ $p->id_pembagian }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Hapus Data">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-10 text-gray-500 italic">
                        Belum ada data pembagian pendamping.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH PEMBAGIAN --}}
<div id="tambahPembagianModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-md p-4 transition-all duration-300">
    <div class="fixed inset-0" onclick="toggleModal('tambahPembagianModal')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100 z-10">
        
        <div class="flex justify-between items-center px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Tambah Pembagian</h3>
                    <p class="text-blue-100 text-xs">Atur penugasan pendamping untuk satu atau beberapa KUBE sekaligus</p>
                </div>
            </div>
            <button type="button" onclick="toggleModal('tambahPembagianModal')" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('pembagian_pendamping.store') }}" method="POST" class="flex flex-col overflow-hidden bg-gray-50/50">
            @csrf
            <div class="p-8 overflow-y-auto space-y-6 flex-grow custom-scrollbar">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- KUBE MULTIPLE SELECT --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">
                                Pilih KUBE <span class="text-gray-400 normal-case font-medium italic">(Bisa ketik & pilih banyak)</span>
                            </label>
                            <select id="select_kube" name="id_kube[]" multiple placeholder="Ketik nama KUBE..." autocomplete="off" class="w-full text-sm" required>
                                @foreach($kubes as $kube)
                                <option value="{{ $kube->id_kube }}">{{ $kube->nama_kube }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Pilih Pendamping</label>
                            <select name="id_pendamping" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                <option value="">-- Pilih Pendamping --</option>
                                @foreach($pendampings as $pendamping)
                                <option value="{{ $pendamping->id_pendamping }}">{{ $pendamping->nama_pendamping }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Tgl Mulai Penugasan</label>
                            <input type="date" name="tgl_pembagian" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-gray-700" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Tgl Selesai <span class="text-gray-400 normal-case font-medium italic">(Opsional)</span></label>
                            <input type="date" name="tgl_selesai" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-gray-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Status Pembagian</label>
                            <select name="status" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer appearance-none font-bold text-gray-700" required>
                                <option value="Aktif" class="text-emerald-600 font-bold">AKTIF</option>
                                <option value="Selesai" class="text-blue-600 font-bold">SELESAI</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            <div class="p-6 bg-white border-t flex gap-4 flex-shrink-0 px-8">
                <button type="button" onclick="toggleModal('tambahPembagianModal')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-[2] px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-200 font-bold transition-all transform active:scale-[0.98]">
                    Simpan Data Pembagian
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    // 🔥 Buka modal otomatis kalau ada error validasi
    let adaError = "{{ $errors->any() ? 'true' : 'false' }}";

    document.addEventListener('DOMContentLoaded', function() {
        if (adaError === "true") {
            toggleModal('tambahPembagianModal');
        }

        // INISIALISASI TOM SELECT
        new TomSelect('#select_kube', {
            plugins: ['remove_button'],
            maxOptions: 200, // Membatasi opsi agar tidak lag
            placeholder: 'Ketik & Pilih KUBE...'
        });
    });

    function confirmSelesai(event, id_pembagian) {
        event.preventDefault();
        Swal.fire({
            title: 'Tandai Selesai?',
            text: "Status penugasan ini akan diubah menjadi Selesai per hari ini!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981', // Warna hijau emerald Tailwind
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Selesaikan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-4 py-2 font-bold',
                cancelButton: 'rounded-xl px-4 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('selesaiForm-' + id_pembagian).submit();
            }
        });
    }

    function confirmDelete(event, id_pembagian) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data Pembagian Pendamping ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Red-500 Tailwind
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
                document.getElementById('deleteForm-' + id_pembagian).submit();
            }
        });
    }
</script>
@endpush
@endsection