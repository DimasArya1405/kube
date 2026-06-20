@extends('admin.layout')

@section('breadcrumb')
    Data Master / <span class="text-gray-800">Data KUBE</span>
@stop

@section('content')
{{-- HEADER --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Data KUBE</h2>
        <p class="text-gray-500 mt-1">Kelola informasi Kelompok Usaha Bersama, status, dan pembagian pendamping.</p>
    </div>
    <div>
        <button type="button" onclick="toggleModal('tambahKubeModal')"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition flex items-center shadow-sm">
            <!-- <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg> -->
            Tambah KUBE
        </button>
    </div>
</div>

{{-- ALERT MESSAGES --}}
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
            <input type="text" class="w-full pl-10 pr-4 py-2.5 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-sm placeholder:text-gray-400" placeholder="Cari nama KUBE...">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('kube.export.pdf') }}" class="px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl hover:bg-red-100 text-sm transition shadow-sm flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> 
                Export PDF
            </a>
            <a href="{{ route('kube.export.excel') }}" class="px-4 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-xl hover:bg-emerald-100 text-sm transition shadow-sm flex items-center font-bold">
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
                    <th class="px-6 py-3 text-center">Lokasi</th>
                    <th class="px-6 py-3 text-center">Cluster Usaha</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kubes as $index => $k)
                <tr class="border-b bg-white hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $k->nama_kube }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $k->desa->nama_desa_kelurahan ?? '-' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $k->clusterUsaha->nama_cluster ?? '-' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                        @if($k->status == 'Aktif')
                            <span class="bg-blue-200 px-2 py-1 text-xs rounded-md text-blue-800">Aktif</span>
                        @else
                            <span class="bg-red-200 px-2 py-1 text-xs rounded-md text-red-800">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Button Detail --}}
                            <a href="{{ route('kube.show', $k->id_kube) }}" class="w-9 h-9 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            
                            {{-- Button Edit --}}
                            <button type="button" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-yellow-500 hover:bg-yellow-50 transition-colors" title="Ubah Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            {{-- Button Delete --}}
                            <form action="{{ route('kube.destroy', $k->id_kube) }}" method="POST" class="inline-block m-0" id="deleteForm-{{ $k->id_kube }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, '{{ $k->id_kube }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Hapus Data">
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
                    <td colspan="6" class="text-center py-10 text-gray-500 italic">
                        Belum ada data KUBE.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH KUBE --}}
<div id="tambahKubeModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-md p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100">
        
        <div class="flex justify-between items-center px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Tambah Data KUBE</h3>
                    <p class="text-blue-100 text-xs">Lengkapi form untuk menambahkan Kelompok Usaha Bersama baru</p>
                </div>
            </div>
            <button type="button" onclick="toggleModal('tambahKubeModal')" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('kube.store') }}" method="POST" class="flex flex-col overflow-hidden bg-gray-50/50">
            @csrf
            <div class="p-8 overflow-y-auto space-y-6 flex-grow custom-scrollbar">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Pilih Akun Ketua KUBE</label>
                            <select name="id_user" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                <option value="">-- Pilih Akun Pendaftar --</option>
                                @foreach($calonKetua as $ketua)
                                <option value="{{ $ketua->id_user }}">{{ $ketua->nama }}</option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-gray-400 mt-1 ml-1 italic">*Pilih akun pengguna yang akan menjadi Ketua di KUBE ini.</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama KUBE</label>
                            <input type="text" name="nama_kube" placeholder="Masukkan Nama KUBE" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all placeholder:text-gray-400" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Asal Desa/Kelurahan</label>
                            <select name="id_desa_kelurahan" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                <option value="">-- Pilih Desa --</option>
                                @foreach($desas as $desa)
                                <option value="{{ $desa->id_desa_kelurahan }}">{{ $desa->nama_desa_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Cluster Usaha</label>
                            <select name="id_cluster" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                <option value="">-- Pilih Cluster --</option>
                                @foreach($clusters as $cluster)
                                <option value="{{ $cluster->id_cluster }}">{{ $cluster->nama_cluster }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Keterangan</label>
                            <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan..." class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border resize-none outline-none transition-all" required></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="p-6 bg-white border-t flex gap-4 flex-shrink-0 px-8">
                <button type="button" onclick="toggleModal('tambahKubeModal')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-[2] px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-200 font-bold transition-all transform active:scale-[0.98]">
                    Simpan Data KUBE
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT KUBE LOOP --}}
@foreach($kubes as $k)
<div id="editKubeModal{{ $k->id_kube }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-md p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100">
        
        <div class="flex justify-between items-center px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Perbarui Data KUBE</h3>
                    <p class="text-amber-50 text-xs">Ubah informasi dan status operasional KUBE</p>
                </div>
            </div>
            <button type="button" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('kube.update', $k->id_kube) }}" method="POST" class="flex flex-col overflow-hidden bg-gray-50/50">
            @csrf
            @method('PUT')
            
            <div class="p-8 overflow-y-auto space-y-6 flex-grow custom-scrollbar">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama KUBE</label>
                            <input type="text" name="nama_kube" value="{{ $k->nama_kube }}" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all" required>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Kecamatan</label>
                            <input type="text" value="{{ $k->desa->kecamatan->nama_kecamatan ?? '-' }}" disabled class="w-full border-gray-200 rounded-xl p-3 bg-gray-100 text-gray-500 cursor-not-allowed border outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Desa/Kelurahan</label>
                            <select name="id_desa_kelurahan" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                @foreach($desas as $desa)
                                <option value="{{ $desa->id_desa_kelurahan }}" {{ $k->id_desa_kelurahan == $desa->id_desa_kelurahan ? 'selected' : '' }}>{{ $desa->nama_desa_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Kategori Usaha</label>
                            <input type="text" value="{{ $k->clusterUsaha->kategori->nama_kategori ?? '-' }}" disabled class="w-full border-gray-200 rounded-xl p-3 bg-gray-100 text-gray-500 cursor-not-allowed border outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Cluster Usaha</label>
                            <select name="id_cluster" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                @foreach($clusters as $cluster)
                                <option value="{{ $cluster->id_cluster }}" {{ $k->id_cluster == $cluster->id_cluster ? 'selected' : '' }}>{{ $cluster->nama_cluster }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Koordinator Wilayah</label>
                            <input type="text" value="{{ $k->pembagianPendamping->pembagianKoordinator->koordinator->nama_koor ?? 'Belum ada Koordinator' }}" disabled class="w-full border-gray-200 rounded-xl p-3 bg-gray-100 text-gray-500 cursor-not-allowed border outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Pendamping KUBE</label>
                            <input type="text" value="{{ $k->pembagianPendamping->pendamping->nama_pendamping ?? 'Belum ada Pendamping' }}" disabled class="w-full border-gray-200 rounded-xl p-3 bg-gray-100 text-gray-500 cursor-not-allowed border outline-none font-medium">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Tanggal Dibentuk</label>
                            <input type="date" name="tanggal_terbentuk" value="{{ $k->tanggal_terbentuk }}" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all text-gray-700">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Status Operasional</label>
                            <select name="status" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border bg-white outline-none transition-all cursor-pointer font-bold uppercase text-sm tracking-wide" required>
                                <option value="Aktif" class="text-green-600" {{ $k->status == 'Aktif' ? 'selected' : '' }}>AKTIF</option>
                                <option value="Tidak Aktif" class="text-red-600" {{ $k->status == 'Tidak Aktif' ? 'selected' : '' }}>TIDAK AKTIF</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Keterangan Lanjutan</label>
                            <textarea name="keterangan" rows="3" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border resize-none outline-none transition-all" placeholder="Tambahkan keterangan...">{{ $k->keterangan }}</textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="p-6 bg-white border-t flex gap-4 flex-shrink-0 px-8">
                <button type="button" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-[2] px-4 py-3 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-xl hover:from-amber-600 hover:to-amber-700 shadow-lg shadow-amber-100 font-bold transition-all transform active:scale-[0.98]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    function confirmDelete(event, id_kube) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data KUBE ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Red-500 Tailwind
            cancelButtonColor: '#9ca3af',  // Gray-400 Tailwind
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
                document.getElementById('deleteForm-' + id_kube).submit();
            }
        });
    }
</script>
@endpush
@endsection