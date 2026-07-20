@extends('koordinator.layout')

@section('breadcrumb')
    Dashboard / Data Master / <span class="text-gray-800">Detail KUBE</span>
@stop

@section('content')
{{-- HEADER DENGAN TOMBOL KEMBALI --}}
<div class="mb-8 flex flex-col md:flex-row md:items-center gap-4">
    <a href="{{ route('kube.index') }}" class="w-11 h-11 bg-white border border-gray-200 shadow-sm rounded-xl flex items-center justify-center text-gray-500 hover:text-[#48CAE4] hover:bg-[#48CAE4]/10 transition-all" title="Kembali">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
    </a>
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Detail KUBE: <span class="text-[#48CAE4] uppercase">{{ $kube->nama_kube }}</span></h2>
        <p class="text-gray-500 mt-1">Kelola informasi Kelompok Usaha Bersama, status, dan pembagian pendamping.</p>
    </div>
</div>

{{-- LAYOUT GRID: KIRI (INFO) & KANAN (ANGGOTA) --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    
    {{-- KOLOM KIRI: INFO KUBE (1 Kolom) --}}
    <div class="xl:col-span-1 space-y-6">
        
        {{-- Card Info Dasar --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-sm font-bold text-gray-500 mb-4 uppercase tracking-wider flex items-center border-b border-gray-100 pb-3">
                <svg class="w-5 h-5 mr-2 text-[#48CAE4]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Informasi Dasar
            </h3>
            <div class="space-y-3.5">
                <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                    <span class="text-gray-500 text-sm font-medium">Nama KUBE</span>
                    <span class="font-bold text-gray-800 text-right uppercase">{{ $kube->nama_kube }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                    <span class="text-gray-500 text-sm font-medium">Kategori</span>
                    <span class="font-bold text-gray-800 text-right">{{ $kube->clusterUsaha->kategori->nama_kategori ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                    <span class="text-gray-500 text-sm font-medium">Cluster</span>
                    <span class="font-bold text-gray-800 text-right">{{ $kube->clusterUsaha->nama_cluster ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                    <span class="text-gray-500 text-sm font-medium">Kecamatan</span>
                    <span class="font-bold text-gray-800 text-right">{{ $kube->desa->kecamatan->nama_kecamatan ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                    <span class="text-gray-500 text-sm font-medium">Desa/Kelurahan</span>
                    <span class="font-bold text-gray-800 text-right">{{ $kube->desa->nama_desa_kelurahan ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center border-b border-gray-50 pb-2.5">
                    <span class="text-gray-500 text-sm font-medium">Status</span>
                    @if(strtolower($kube->status) == 'aktif')
                        <span class="bg-emerald-100 border border-emerald-200 text-emerald-700 px-2.5 py-1 rounded-md text-xs font-bold">Aktif</span>
                    @else
                        <span class="bg-red-100 border border-red-200 text-red-700 px-2.5 py-1 rounded-md text-xs font-bold">{{ $kube->status }}</span>
                    @endif
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 text-sm font-medium">Tgl Terbentuk</span>
                    <span class="font-bold text-gray-800 text-right">{{ $kube->tanggal_terbentuk ? \Carbon\Carbon::parse($kube->tanggal_terbentuk)->format('d F Y') : '-' }}</span>
                </div>
            </div>
        </div>

        {{-- Card Pengelola --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-sm font-bold text-gray-500 mb-4 uppercase tracking-wider flex items-center border-b border-gray-100 pb-3">
                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Pengelola KUBE
            </h3>
            <div class="space-y-4">
                <div class="flex items-center p-3.5 bg-[#48CAE4]/10 rounded-xl border border-[#48CAE4]/20">
                    <div class="bg-white text-[#48CAE4] w-11 h-11 rounded-full flex items-center justify-center mr-3 shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] text-[#48CAE4] font-bold uppercase tracking-wider">Pendamping</p>
                        <p class="text-sm font-bold text-gray-800">{{ $kube->pembagianPendamping->pendamping->nama_pendamping ?? 'Belum ada Pendamping' }}</p>
                    </div>
                </div>

                <div class="flex items-center p-3.5 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="bg-white text-emerald-600 w-11 h-11 rounded-full flex items-center justify-center mr-3 shrink-0 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] text-emerald-500 font-bold uppercase tracking-wider">Koordinator</p>
                        <p class="text-sm font-bold text-gray-800">{{ $kube->pembagianPendamping->pembagianKoordinator->koordinator->nama_koor ?? 'Belum ada Koordinator' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Keterangan --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-sm font-bold text-gray-500 mb-3 uppercase tracking-wider flex items-center border-b border-gray-100 pb-3">
                <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Keterangan
            </h3>
            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 text-sm text-gray-600 leading-relaxed italic font-medium">
                {{ $kube->keterangan ?? 'Tidak ada keterangan.' }}
            </div>
        </div>

    </div>

    {{-- KOLOM KANAN: DAFTAR ANGGOTA (2 Kolom) --}}
    <div class="xl:col-span-2 flex flex-col">
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 h-full flex flex-col overflow-hidden">
            {{-- Table Header & Actions --}}
            <div class="p-5 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">Daftar Anggota KUBE</h3>
                    <p class="text-sm text-gray-500 font-medium">Total: {{ $kube->anggota->count() }} Anggota</p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <div class="relative w-full sm:w-64">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#48CAE4] outline-none text-sm bg-white transition-all placeholder:text-gray-400" placeholder="Cari nama Anggota...">
                    </div>
                    <button onclick="toggleModal('tambahAnggotaModal')" class="px-4 py-2.5 bg-[#48CAE4] text-white text-sm font-medium rounded-lg hover:bg-[#3bb3cc] transition shadow-sm flex items-center justify-center shrink-0">
                        Tambah Anggota
                    </button>
                </div>
            </div>

            {{-- Table Body --}}
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="py-4 px-5 text-center w-12 font-bold">No</th>
                            <th class="py-4 px-5 font-bold">Nama Anggota & NIK</th>
                            <th class="py-4 px-5 text-center font-bold">Jabatan</th>
                            <th class="py-4 px-5 font-bold">Kontak & Alamat</th>
                            <th class="py-4 px-5 text-center font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($kube->anggota as $index => $anggota)
                        <tr class="hover:bg-gray-50 transition-colors bg-white">
                            <td class="py-4 px-5 text-center text-gray-800 font-bold">{{ $index + 1 }}</td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-gray-900">{{ $anggota->nama_anggota }}</div>
                                <div class="text-xs text-gray-500 font-mono tracking-widest mt-0.5">{{ $anggota->nik }}</div>
                            </td>
                            <td class="py-4 px-5 text-center">
                                @php
                                    $jabatan = strtolower($anggota->jabatan);
                                @endphp

                                @if($jabatan == 'ketua')
                                    <span class="bg-emerald-100 px-3 py-1 text-xs rounded-md border border-emerald-200 text-emerald-800 font-semibold">Ketua</span>
                                @elseif($jabatan == 'sekretaris')
                                    <span class="bg-[#48CAE4]/20 px-3 py-1 text-xs rounded-md border border-[#48CAE4]/30 text-[#2b889c] font-semibold">Sekretaris</span>
                                @elseif($jabatan == 'bendahara')
                                    <span class="bg-amber-100 px-3 py-1 text-xs rounded-md border border-amber-200 text-amber-800 font-semibold">Bendahara</span>
                                @else
                                    <span class="bg-gray-100 px-3 py-1 text-xs rounded-md border border-gray-200 text-gray-800 font-semibold">Anggota</span>
                                @endif
                            </td>
                            <td class="py-4 px-5">
                                <div class="text-gray-800 font-medium flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    {{ $anggota->no_hp }}
                                </div>
                                <div class="text-[11px] text-gray-500 mt-1 truncate max-w-[200px]" title="{{ $anggota->alamat }}">{{ $anggota->alamat }}</div>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-yellow-500 hover:bg-yellow-50 transition-colors" title="Edit Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    @if($anggota->nik !== Auth::user()->nik)
                                    <form action="{{ route('anggota_kube.destroy', $anggota->id_anggota) }}" method="POST" class="inline-block m-0" id="deleteAnggotaForm-{{ $anggota->id_anggota }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteAnggota(event, '{{ $anggota->id_anggota }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Keluarkan Anggota">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    @else
                                    <div class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-300 cursor-not-allowed" title="Anda tidak bisa menghapus diri sendiri">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path>
                                    </svg>
                                    <p class="text-base font-bold text-gray-500">Belum ada anggota di KUBE ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL EDIT ANGGOTA ================= --}}
@foreach($kube->anggota as $anggota)
<div id="editAnggotaModal{{ $anggota->id_anggota }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Perbarui Data Anggota</h3>
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
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik" value="{{ $anggota->nik }}" maxlength="16" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_anggota" value="{{ $anggota->nama_anggota }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        @if($anggota->jabatan == 'Ketua')
                        <input type="text" value="Ketua (Tidak bisa diubah)" disabled class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed">
                        <input type="hidden" name="jabatan" value="Ketua">
                        @else
                        <select name="jabatan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm" required>
                            <option value="Sekretaris" {{ $anggota->jabatan == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                            <option value="Bendahara" {{ $anggota->jabatan == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                            <option value="Anggota" {{ $anggota->jabatan == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                        </select>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Handphone</label>
                        <input type="text" name="no_hp" value="{{ $anggota->no_hp }}" maxlength="15" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm resize-none" required>{{ $anggota->alamat }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-[#48CAE4] text-white rounded-lg hover:bg-[#3bb3cc] transition text-sm font-medium">
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Asal KUBE</label>
                        <input type="text" value="{{ $kube->nama_kube }}" disabled class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed">
                        <input type="hidden" name="id_kube" value="{{ $kube->id_kube }}">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik" maxlength="16" placeholder="16 Digit NIK" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm placeholder:text-gray-400" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota</label>
                        <input type="text" name="nama_anggota" placeholder="Nama Sesuai KTP" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm placeholder:text-gray-400" required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <select name="jabatan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <option value="Sekretaris">Sekretaris</option>
                            <option value="Bendahara">Bendahara</option>
                            <option value="Anggota">Anggota</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Handphone</label>
                        <input type="text" name="no_hp" maxlength="15" placeholder="08xxxxx" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm placeholder:text-gray-400" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap domisili..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#48CAE4] text-sm resize-none placeholder:text-gray-400" required></textarea>
                    </div>
                    
                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-[#48CAE4] text-white rounded-lg hover:bg-[#3bb3cc] transition text-sm font-medium shadow-sm">
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

    function confirmDeleteAnggota(event, id_anggota) {
        event.preventDefault();
        Swal.fire({
            title: 'Keluarkan Anggota?',
            text: "Data anggota ini akan dihapus permanen dari KUBE!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Keluarkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-4 py-2 font-bold',
                cancelButton: 'rounded-xl px-4 py-2 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAnggotaForm-' + id_anggota).submit();
            }
        });
    }
</script>
@endpush
@endsection