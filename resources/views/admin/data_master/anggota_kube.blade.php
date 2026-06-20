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
            <!-- <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
            </svg> -->
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
            <a href="{{ route('anggota.export.pdf') }}" class="px-4 py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl hover:bg-red-100 text-sm transition shadow-sm flex items-center font-bold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> 
                Export PDF
            </a>
            <a href="{{ route('anggota.export.excel') }}" class="px-4 py-2.5 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-xl hover:bg-emerald-100 text-sm transition shadow-sm flex items-center font-bold">
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

{{-- MODAL LOOPING (DETAIL & EDIT) --}}
@foreach($anggotas as $index => $anggota)

{{-- MODAL DETAIL --}}
<div id="detailAnggotaModal{{ $anggota->id_anggota }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all duration-300">
    <div class="fixed inset-0" onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all z-10">
        
        <div class="relative h-28 bg-gradient-to-r from-blue-600 to-indigo-700">
            <button onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')" class="absolute top-4 right-4 text-white/80 hover:text-white p-2 rounded-full hover:bg-white/10 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="px-6 pb-6 text-center">
            <div class="relative -mt-12 mb-4 inline-block">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full border-4 border-white shadow-lg text-blue-600 text-3xl font-bold uppercase">
                    {{ substr($anggota->nama_anggota, 0, 1) }}
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-800">{{ $anggota->nama_anggota }}</h3>
                <span class="inline-flex items-center px-3 py-1 mt-1 rounded-md text-xs font-bold bg-blue-100 text-blue-800 capitalize">{{ $anggota->jabatan }}</span>
            </div>

            <div class="space-y-3 text-left">
                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Nomor Induk Kependudukan (NIK)</p>
                    <p class="text-sm font-semibold text-gray-700 tracking-widest">{{ $anggota->nik }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Asal KUBE</p>
                        <p class="text-sm font-semibold text-gray-700 truncate" title="{{ $anggota->kube->nama_kube ?? '-' }}">{{ $anggota->kube->nama_kube ?? '-' }}</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">No. Handphone</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $anggota->no_hp }}</p>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 shadow-sm">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Alamat Lengkap</p>
                    <p class="text-sm font-semibold text-gray-700 mt-1">{{ $anggota->alamat }}</p>
                </div>
            </div>

            <div class="mt-6 flex justify-center">
                <button onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-all text-sm">
                    Tutup Profil
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="editAnggotaModal{{ $anggota->id_anggota }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-md p-4 transition-all duration-300">
    <div class="fixed inset-0" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100 z-10">
        
        <div class="flex justify-between items-center px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Perbarui Anggota</h3>
                    <p class="text-amber-50 text-xs">Ubah informasi personal dan jabatan anggota</p>
                </div>
            </div>
            <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('anggota_kube.update', $anggota->id_anggota) }}" method="POST" class="flex flex-col overflow-hidden bg-gray-50/50">
            @csrf
            @method('PUT')
            
            <div class="p-8 overflow-y-auto space-y-6 flex-grow custom-scrollbar">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Asal KUBE</label>
                            <select name="id_kube" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                @foreach($kubes as $kube)
                                <option value="{{ $kube->id_kube }}" {{ $anggota->id_kube == $kube->id_kube ? 'selected' : '' }}>{{ $kube->nama_kube }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">NIK</label>
                            <input type="text" name="nik" value="{{ $anggota->nik }}" maxlength="16" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama Lengkap</label>
                            <input type="text" name="nama_anggota" value="{{ $anggota->nama_anggota }}" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Jabatan</label>
                            <select name="jabatan" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                <option value="Ketua" {{ $anggota->jabatan == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                                <option value="Sekretaris" {{ $anggota->jabatan == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                                <option value="Bendahara" {{ $anggota->jabatan == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                                <option value="Anggota" {{ $anggota->jabatan == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">No. Handphone</label>
                            <input type="text" name="no_hp" value="{{ $anggota->no_hp }}" maxlength="15" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none border transition-all" required>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 border resize-none outline-none transition-all" required>{{ $anggota->alamat }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border-t flex gap-4 flex-shrink-0 px-8">
                <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold transition-all">
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

{{-- MODAL TAMBAH ANGGOTA --}}
<div id="tambahAnggotaModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-md p-4 transition-all duration-300">
    <div class="fixed inset-0" onclick="toggleModal('tambahAnggotaModal')"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh] border border-gray-100 z-10">
        
        <div class="flex justify-between items-center px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Tambah Data Anggota</h3>
                    <p class="text-blue-100 text-xs">Lengkapi form untuk mendaftarkan anggota baru ke dalam KUBE</p>
                </div>
            </div>
            <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="text-white/70 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('anggota_kube.store') }}" method="POST" class="flex flex-col overflow-hidden bg-gray-50/50">
            @csrf
            <div class="p-8 overflow-y-auto space-y-6 flex-grow custom-scrollbar">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Pilih Asal KUBE</label>
                            <select name="id_kube" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                <option value="">-- Pilih KUBE --</option>
                                @foreach($kubes as $kube)
                                <option value="{{ $kube->id_kube }}">{{ $kube->nama_kube }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" name="nik" maxlength="16" placeholder="Masukkan 16 digit NIK" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all placeholder:text-gray-400" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Nama Lengkap Anggota</label>
                            <input type="text" name="nama_anggota" placeholder="Masukkan Nama Sesuai KTP" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all placeholder:text-gray-400" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Jabatan dalam KUBE</label>
                            <select name="jabatan" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border bg-white outline-none transition-all cursor-pointer appearance-none" required>
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Bendahara">Bendahara</option>
                                <option value="Anggota">Anggota</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">No. Handphone</label>
                            <input type="text" name="no_hp" maxlength="15" placeholder="Contoh: 08123456789" class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all placeholder:text-gray-400" required>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5 ml-1">Alamat Lengkap (Domisili)</label>
                            <textarea name="alamat" rows="3" placeholder="Masukkan nama jalan, dusun, RT/RW..." class="w-full border-gray-200 rounded-xl p-3 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 border resize-none outline-none transition-all" required></textarea>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border-t flex gap-4 flex-shrink-0 px-8">
                <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 font-bold transition-all">
                    Batal
                </button>
                <button type="submit" class="flex-[2] px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-200 font-bold transition-all transform active:scale-[0.98]">
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