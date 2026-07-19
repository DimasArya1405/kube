@extends('koordinator.layout')

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
<form action="{{ route('kube.index') }}" method="GET" class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            {{-- Tambahkan name="search" dan value supaya teks tidak hilang setelah dicari --}}
            <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-sm placeholder:text-gray-400" placeholder="Cari nama KUBE...">
        </form>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('kube.export.pdf') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm transition shadow-sm flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> 
                Export PDF
            </a>
            <a href="{{ route('kube.export.excel') }}" class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 text-sm transition shadow-sm flex items-center font-bold">
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
            <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                <tr>
                    <th class="px-6 py-4 text-center">No.</th>
                    <th class="px-6 py-4">Nama KUBE</th>
                    <th class="px-6 py-4">Lokasi</th>
                    <th class="px-6 py-4 text-center">Cluster Usaha</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kubes as $index => $k)
                <tr class="border-b bg-white hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $index + 1 }}.</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $k->nama_kube }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $k->desa->nama_desa_kelurahan ?? '-' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $k->clusterUsaha->nama_cluster ?? '-' }}</td>

                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                        @if($k->status == 'Aktif')
                            <span class="bg-emerald-100 border border-emerald-200 px-2 py-1 text-xs rounded-md text-emerald-700 font-semibold">Aktif</span>
                        @else
                            <span class="bg-red-100 border border-red-200 px-2 py-1 text-xs rounded-md text-red-700 font-semibold">Tidak Aktif</span>
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
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div> 

{{-- ================= MODAL EDIT KUBE LOOP ================= --}}
@foreach($kubes as $k)
<div id="editKubeModal{{ $k->id_kube }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Perbarui Data KUBE</h3>
            <button type="button" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('kube.update', $k->id_kube) }}" method="POST" class="flex flex-col overflow-hidden flex-1">
            @csrf
            @method('PUT')
            
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama KUBE</label>
                        <input type="text" name="nama_kube" value="{{ $k->nama_kube }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                        <input type="text" value="{{ $k->desa->kecamatan->nama_kecamatan ?? '-' }}" disabled class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desa/Kelurahan</label>
                        <select name="id_desa_kelurahan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            @foreach($desas as $desa)
                            <option value="{{ $desa->id_desa_kelurahan }}" {{ $k->id_desa_kelurahan == $desa->id_desa_kelurahan ? 'selected' : '' }}>{{ $desa->nama_desa_kelurahan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input type="text" value="{{ $k->clusterUsaha->kategori->nama_kategori ?? '-' }}" disabled class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cluster</label>
                        <select name="id_cluster" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            @foreach($clusters as $cluster)
                            <option value="{{ $cluster->id_cluster }}" {{ $k->id_cluster == $cluster->id_cluster ? 'selected' : '' }}>{{ $cluster->nama_cluster }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Koordinator</label>
                        <input type="text" value="{{ $k->pembagianPendamping->pembagianKoordinator->koordinator->nama_koor ?? 'Belum ada Koordinator' }}" disabled class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendamping</label>
                        <input type="text" value="{{ $k->pembagianPendamping->pendamping->nama_pendamping ?? 'Belum ada Pendamping' }}" disabled class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dibentuk</label>
                        <input type="date" name="tanggal_terbentuk" value="{{ $k->tanggal_terbentuk }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Operasional</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="Aktif" {{ $k->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ $k->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none" placeholder="Tambahkan keterangan jika ada...">{{ $k->keterangan }}</textarea>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- ================= MODAL TAMBAH KUBE ================= --}}
<div id="tambahKubeModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('tambahKubeModal')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Data KUBE</h3>
            <button type="button" onclick="toggleModal('tambahKubeModal')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('kube.store') }}" method="POST" class="flex flex-col overflow-hidden flex-1">
            @csrf
            
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Akun Ketua KUBE</label>
                        <select name="id_user" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="">-- Pilih Akun Pendaftar --</option>
                            @foreach($calonKetua as $ketua)
                            <option value="{{ $ketua->id_user }}">{{ $ketua->nama }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">*Pilih akun pengguna yang akan menjadi Ketua di KUBE ini.</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama KUBE</label>
                        <input type="text" name="nama_kube" placeholder="Masukkan Nama KUBE" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder:text-gray-400" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Asal Desa/Kelurahan</label>
                        <select name="id_desa_kelurahan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="">-- Pilih Desa --</option>
                            @foreach($desas as $desa)
                            <option value="{{ $desa->id_desa_kelurahan }}">{{ $desa->nama_desa_kelurahan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cluster Usaha</label>
                        <select name="id_cluster" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="">-- Pilih Cluster --</option>
                            @foreach($clusters as $cluster)
                            <option value="{{ $cluster->id_cluster }}">{{ $cluster->nama_cluster }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none placeholder:text-gray-400" required></textarea>
                    </div>
                    
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('tambahKubeModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    Simpan Data KUBE
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

    function confirmDelete(event, id_kube) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data KUBE ini akan dihapus secara permanen!",
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
                document.getElementById('deleteForm-' + id_kube).submit();
            }
        });
    }
</script>
@endpush
@endsection