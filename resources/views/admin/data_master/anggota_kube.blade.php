@extends('admin.layout')

@section('breadcrumb')
    Data Master / <span class="text-gray-800">Data Anggota KUBE</span>
@stop

@section('content')
{{-- HEADER --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Data Anggota</h2>
        <p class="text-gray-500 mt-1">Kelola informasi seluruh anggota KUBE yang terdaftar, termasuk NIK, jabatan, dan kontak.</p>
    </div>
    <div>
        <button type="button" onclick="toggleModal('tambahAnggotaModal')"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition flex items-center shadow-sm">
            Tambah Anggota
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
            <input type="text" class="w-full pl-10 pr-4 py-2.5 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-sm placeholder:text-gray-400" placeholder="Cari nama Anggota...">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('anggota.export.pdf') }}" class="px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm transition shadow-sm flex items-center font-bold">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> 
    Export PDF
</a>
<a href="{{ route('anggota.export.excel') }}" class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 text-sm transition shadow-sm flex items-center font-bold">
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
                    <th class="px-6 py-3 text-center">NIK</th>
                    <th class="px-6 py-3">Nama Anggota</th>
                    <th class="px-6 py-3">Asal KUBE</th>
                    <th class="px-6 py-3 text-center">Jabatan</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($anggotas as $index => $anggota)
                <tr class="border-b bg-white hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-600 text-center tracking-wider">{{ $anggota->nik }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $anggota->nama_anggota }}</td>
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $anggota->kube->nama_kube ?? '-' }}</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $jabatan = strtolower($anggota->jabatan);
                        @endphp

                        @if($jabatan == 'ketua')
                            <span class="bg-emerald-200 px-3 py-1 text-xs rounded-md text-emerald-800 font-bold">Ketua</span>
                        @elseif($jabatan == 'sekretaris')
                            <span class="bg-blue-200 px-3 py-1 text-xs rounded-md text-blue-800 font-bold">Sekretaris</span>
                        @elseif($jabatan == 'bendahara')
                            <span class="bg-amber-200 px-3 py-1 text-xs rounded-md text-amber-800 font-bold">Bendahara</span>
                        @else
                            <span class="bg-gray-200 px-3 py-1 text-xs rounded-md text-gray-800 font-bold">Anggota</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Button Detail --}}
                            <button type="button" onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            
                            {{-- Button Edit --}}
                            <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-yellow-500 hover:bg-yellow-50 transition-colors" title="Ubah Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            {{-- Button Delete --}}
                            <form action="{{ route('anggota_kube.destroy', $anggota->id_anggota) }}" method="POST" class="inline-block m-0" id="deleteForm-{{ $anggota->id_anggota }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, '{{ $anggota->id_anggota }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Hapus Data">
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
                        Belum ada data anggota KUBE.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL LOOPING (DETAIL & EDIT) ================= --}}
@foreach($anggotas as $index => $anggota)

{{-- MODAL DETAIL --}}
<div id="detailAnggotaModal{{ $anggota->id_anggota }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Detail Anggota</h3>
            <button type="button" onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
            <table class="w-full text-sm text-left text-gray-600">
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <th class="py-3 font-medium text-gray-900 w-1/3">Nama Lengkap</th>
                        <td class="py-3 text-gray-700 font-semibold">{{ $anggota->nama_anggota }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">NIK</th>
                        <td class="py-3 text-gray-700">{{ $anggota->nik }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">Jabatan</th>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">{{ $anggota->jabatan }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">No. Handphone</th>
                        <td class="py-3 text-gray-700">{{ $anggota->no_hp }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">Asal KUBE</th>
                        <td class="py-3 text-gray-700">{{ $anggota->kube->nama_kube ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900 align-top">Alamat Lengkap</th>
                        <td class="py-3 text-gray-700">{{ $anggota->alamat }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="button" onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="editAnggotaModal{{ $anggota->id_anggota }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Ubah Data Anggota</h3>
            <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('anggota_kube.update', $anggota->id_anggota) }}" method="POST" class="flex flex-col overflow-hidden flex-1">
            @csrf
            @method('PUT')
            
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Asal KUBE</label>
                        <select name="id_kube" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            @foreach($kubes as $kube)
                            <option value="{{ $kube->id_kube }}" {{ $anggota->id_kube == $kube->id_kube ? 'selected' : '' }}>{{ $kube->nama_kube }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik" value="{{ $anggota->nik }}" maxlength="16" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_anggota" value="{{ $anggota->nama_anggota }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <select name="jabatan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="Ketua" {{ $anggota->jabatan == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                            <option value="Sekretaris" {{ $anggota->jabatan == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                            <option value="Bendahara" {{ $anggota->jabatan == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                            <option value="Anggota" {{ $anggota->jabatan == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Handphone</label>
                        <input type="text" name="no_hp" value="{{ $anggota->no_hp }}" maxlength="15" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none" required>{{ $anggota->alamat }}</textarea>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
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

{{-- ================= MODAL TAMBAH ANGGOTA ================= --}}
<div id="tambahAnggotaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('tambahAnggotaModal')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Data Anggota</h3>
            <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('anggota_kube.store') }}" method="POST" class="flex flex-col overflow-hidden flex-1">
            @csrf
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Asal KUBE</label>
                        <select name="id_kube" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="">-- Pilih KUBE --</option>
                            @foreach($kubes as $kube)
                            <option value="{{ $kube->id_kube }}">{{ $kube->nama_kube }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" name="nik" maxlength="16" placeholder="Masukkan 16 digit NIK" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder:text-gray-400" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap Anggota</label>
                        <input type="text" name="nama_anggota" placeholder="Masukkan Nama Sesuai KTP" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder:text-gray-400" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan dalam KUBE</label>
                        <select name="jabatan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Ketua">Ketua</option>
                            <option value="Sekretaris">Sekretaris</option>
                            <option value="Bendahara">Bendahara</option>
                            <option value="Anggota">Anggota</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Handphone</label>
                        <input type="text" name="no_hp" maxlength="15" placeholder="Contoh: 08123456789" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder:text-gray-400" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap (Domisili)</label>
                        <textarea name="alamat" rows="3" placeholder="Masukkan nama jalan, dusun, RT/RW..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none placeholder:text-gray-400" required></textarea>
                    </div>
                    
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
                    Simpan Data Anggota
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

    function confirmDelete(event, id_anggota) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data Anggota ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Red-500
            cancelButtonColor: '#9ca3af',  // Gray-400
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
                document.getElementById('deleteForm-' + id_anggota).submit();
            }
        });
    }
</script>
@endpush
@endsection