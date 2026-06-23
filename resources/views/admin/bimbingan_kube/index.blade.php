@extends('admin.layout')

@section('breadcrumb')
    Bimbingan / <span class="text-gray-800">Data Bimbingan KUBE</span>
@stop

@section('content')
{{-- HEADER --}}
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Bimbingan KUBE oleh Pendamping</h2>
        <p class="text-gray-500 mt-1">Kelola laporan dan hasil bimbingan kelompok usaha bersama.</p>
    </div>
    <div>
        <button type="button" onclick="toggleModal('tambahBimbinganModal')"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition flex items-center shadow-sm">
            Tambah Data
        </button>
    </div>
</div>

{{-- ALERT MESSAGES --}}
@if ($errors->any())
<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative mb-4 shadow-sm" role="alert">
    <strong class="font-bold">Terdapat kesalahan:</strong>
    <ul class="list-disc pl-5 mt-1 text-sm font-medium">
        @foreach ($errors->all() as $error)
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

{{-- FILTER & EXPORT AREA --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border p-4">
    <div class="flex flex-col md:flex-row justify-between md:items-end gap-4">
        
        {{-- FILTER FORM --}}
        <form action="{{ route('bimbingan.index') }}" method="GET" class="flex flex-wrap items-end gap-3 w-full md:w-auto">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}"
                    class="px-4 py-2.5 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-sm bg-gray-50">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}"
                    class="px-4 py-2.5 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none border transition-all text-sm bg-gray-50">
            </div>

            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 text-sm transition shadow-sm font-bold">
                Filter
            </button>

            @if(request('from') || request('to'))
                <a href="{{ route('bimbingan.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 text-sm transition shadow-sm font-bold border border-gray-200">
                    Reset
                </a>
            @endif
        </form>

        {{-- EXPORT PDF --}}
        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('bimbingan.pdf', ['from' => request('from'), 'to' => request('to')]) }}" 
               class="px-4 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm transition shadow-sm flex items-center font-bold w-full justify-center md:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg> 
                Export PDF
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
                    <th class="px-6 py-3 text-center">Tanggal</th>
                    <th class="px-6 py-3 text-center">KUBE</th>
                    <th class="px-6 py-3 text-center">Pendamping</th>
                    <th class="px-6 py-3 text-center">Jenis</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($datas as $index => $d)
                <tr class="border-b bg-white hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">{{ $index + 1 }}</td>
                    
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                        {{ $d->tanggal_bimbingan ? \Carbon\Carbon::parse($d->tanggal_bimbingan)->format('d/m/Y') : '-' }}
                    </td>
                    
                    <td class="px-6 py-4 font-semibold text-blue-600 text-center">
                        {{ optional($d->kube)->nama_kube ?? '-' }}
                    </td>
                    
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                        {{ $d->id_pendamping == 1 ? 'Siti Aryani' : '-' }}
                    </td>
                    
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                        {{ $d->jenis_bimbingan }}
                    </td>
                    
                    <td class="px-6 py-4 font-medium text-gray-900 text-center">
                        @if($d->status_bimbingan == 'Terlaksana')
                            <span class="bg-emerald-100 border border-emerald-200 px-2 py-1 text-xs rounded-md text-emerald-700 font-semibold">Terlaksana</span>
                        @elseif($d->status_bimbingan == 'Dijadwalkan')
                            <span class="bg-yellow-100 border border-yellow-200 px-2 py-1 text-xs rounded-md text-yellow-700 font-semibold">Dijadwalkan</span>
                        @else
                            <span class="bg-red-100 border border-red-200 px-2 py-1 text-xs rounded-md text-red-700 font-semibold">Ditunda</span>
                        @endif
                    </td>
                    
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            {{-- Button Detail --}}
                            <button type="button" onclick="toggleModal('detailBimbinganModal{{ $d->id_bimbingan }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Button Edit --}}
                            <a href="{{ route('bimbingan.edit', $d->id_bimbingan) }}" class="w-9 h-9 flex items-center justify-center rounded-lg text-yellow-500 hover:bg-yellow-50 transition-colors" title="Ubah Data">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Button Delete dengan SweetAlert --}}
                            <form action="{{ route('bimbingan.destroy', $d->id_bimbingan) }}" method="POST" class="inline-block m-0" id="deleteForm-{{ $d->id_bimbingan }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(event, '{{ $d->id_bimbingan }}')" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Hapus Data">
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
                        Belum ada data bimbingan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODAL LOOPING (DETAIL) ================= --}}
@foreach($datas as $d)
<div id="detailBimbinganModal{{ $d->id_bimbingan }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('detailBimbinganModal{{ $d->id_bimbingan }}')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Detail Bimbingan KUBE</h3>
            <button type="button" onclick="toggleModal('detailBimbinganModal{{ $d->id_bimbingan }}')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
            <table class="w-full text-sm text-left text-gray-600">
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <th class="py-3 font-medium text-gray-900 w-1/3">KUBE</th>
                        <td class="py-3 text-gray-700 font-semibold text-blue-600">{{ optional($d->kube)->nama_kube ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">ID Jadwal</th>
                        <td class="py-3 text-gray-700">{{ $d->id_jadwal }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">Pendamping</th>
                        <td class="py-3 text-gray-700">{{ $d->id_pendamping == 1 ? 'Siti Aryani' : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">Tanggal Bimbingan</th>
                        <td class="py-3 text-gray-700">{{ $d->tanggal_bimbingan ? \Carbon\Carbon::parse($d->tanggal_bimbingan)->format('d F Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">Jenis Bimbingan</th>
                        <td class="py-3">
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">{{ $d->jenis_bimbingan }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">Materi Bimbingan</th>
                        <td class="py-3 text-gray-700">{{ $d->materi_bimbingan }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">Status</th>
                        <td class="py-3 text-gray-700">
                            @if($d->status_bimbingan == 'Terlaksana')
                                <span class="bg-emerald-100 border border-emerald-200 px-2 py-1 text-xs rounded-md text-emerald-700 font-semibold">Terlaksana</span>
                            @elseif($d->status_bimbingan == 'Dijadwalkan')
                                <span class="bg-yellow-100 border border-yellow-200 px-2 py-1 text-xs rounded-md text-yellow-700 font-semibold">Dijadwalkan</span>
                            @else
                                <span class="bg-red-100 border border-red-200 px-2 py-1 text-xs rounded-md text-red-700 font-semibold">Ditunda</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900 align-top">Hasil Bimbingan</th>
                        <td class="py-3 text-gray-700 whitespace-pre-line">{{ $d->hasil_bimbingan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900 align-top">Tindak Lanjut</th>
                        <td class="py-3 text-gray-700 whitespace-pre-line">{{ $d->tindak_lanjut ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="py-3 font-medium text-gray-900">Lampiran</th>
                        <td class="py-3 text-gray-700">
                            @if($d->lampiran)
                                <a href="{{ asset('storage/' . $d->lampiran) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    Lihat Dokumen
                                </a>
                            @else
                                <span class="italic text-gray-400">Tidak ada lampiran</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="button" onclick="toggleModal('detailBimbinganModal{{ $d->id_bimbingan }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                Tutup
            </button>
        </div>
    </div>
</div>
@endforeach

{{-- ================= MODAL TAMBAH BIMBINGAN ================= --}}
<div id="tambahBimbinganModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    <div class="fixed inset-0" onclick="toggleModal('tambahBimbinganModal')"></div>
    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Data Bimbingan KUBE</h3>
            <button type="button" onclick="toggleModal('tambahBimbinganModal')" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('bimbingan.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden flex-1">
            @csrf
            
            <div class="p-6 overflow-y-auto flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    {{-- JADWAL --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Jadwal</label>
                        <input type="number" name="id_jadwal" placeholder="Masukkan ID Jadwal" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder:text-gray-400" required>
                    </div>

                    {{-- AUTO PENDAMPING --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pendamping</label>
                        <input type="text" value="Siti Aryani" class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-2 text-gray-500 text-sm cursor-not-allowed" readonly>
                        <input type="hidden" name="id_pendamping" value="1">
                    </div>

                    {{-- KUBE --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih KUBE</label>
                        <select name="id_kube" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="">-- Pilih KUBE --</option>
                            @foreach($kubes as $k)
                                <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- JENIS --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Bimbingan</label>
                        <select name="jenis_bimbingan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Manajemen Usaha">Manajemen Usaha</option>
                            <option value="Pencatatan Keuangan">Pencatatan Keuangan</option>
                            <option value="Strategi Pemasaran">Strategi Pemasaran</option>
                            <option value="Motivasi">Motivasi</option>
                            <option value="Mediasi">Mediasi</option>
                        </select>
                    </div>

                    {{-- MATERI --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Materi Bimbingan</label>
                        <input type="text" name="materi_bimbingan" placeholder="Contoh: Pencatatan Laba Rugi" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm placeholder:text-gray-400">
                    </div>

                    {{-- TANGGAL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bimbingan</label>
                        <input type="date" name="tanggal_bimbingan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                    </div>

                    {{-- STATUS --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status_bimbingan" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                            <option value="Terlaksana">Terlaksana</option>
                            <option value="Dijadwalkan">Dijadwalkan</option>
                            <option value="Ditunda">Ditunda</option>
                        </select>
                    </div>

                    {{-- HASIL --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hasil Bimbingan</label>
                        <textarea name="hasil_bimbingan" rows="3" placeholder="Deskripsikan hasil bimbingan..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none placeholder:text-gray-400"></textarea>
                    </div>

                    {{-- TINDAK LANJUT --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tindak Lanjut</label>
                        <textarea name="tindak_lanjut" rows="3" placeholder="Rencana tindak lanjut..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none placeholder:text-gray-400"></textarea>
                    </div>

                    {{-- LAMPIRAN --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lampiran Dokumen</label>
                        <input type="file" name="lampiran" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                    </div>

                </div>
            </div>

            <div class="p-4 border-t bg-gray-50 flex justify-end gap-2">
                <button type="button" onclick="toggleModal('tambahBimbinganModal')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    Simpan Data
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

    function confirmDelete(event, id_bimbingan) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data bimbingan ini akan dihapus secara permanen!",
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
                document.getElementById('deleteForm-' + id_bimbingan).submit();
            }
        });
    }
</script>
@endpush
@endsection