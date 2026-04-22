@extends('admin.layout')

@section('title', 'Data Pendamping - KUBE')

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold">Data Pendamping</h2>
        <p class="text-gray-500">Kelola data pendamping</p>
    </div>
</div>

{{-- SUMMARY --}}
<div class="flex gap-4 mb-6">
    <div class="bg-orange-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Pendamping Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $pendamping->where('status','Aktif')->count() }}</p>
    </div>
    <div class="bg-green-300 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Pendamping Non-Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $pendamping->where('status','Tidak Aktif')->count() }}</p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">

    <div class="relative flex-1 min-w-[200px]">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
            </svg>
        </span>
        <input type="text" id="searchInput" placeholder="Cari..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    <a href="{{ route('pendamping.export.pdf') }}"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        </svg>
        Ekspor PDF
    </a>

    <a href="{{ route('pendamping.export.excel') }}"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10" />
        </svg>
        Ekspor Excel
    </a>

    <button onclick="openModal()"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + Tambah Pendamping
    </button>

</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Foto</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">NIK</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kecamatan</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No HP</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($pendamping as $item)
            <tr class="searchable-row hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    @if($item->foto)
                    <img src="{{ asset('storage/foto_pendamping/'.$item->foto) }}"
                        class="w-10 h-10 rounded-full object-cover border border-gray-100 shadow-sm">
                    @else
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->nama_pendamping }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $item->nik }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $item->no_hp }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->status == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $item->status }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">

                        {{-- Tombol Detail (Biru) --}}
                        <button type="button"
                            onclick="openDetailModal('{{ $item->id_pendamping }}')"
                            class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm border border-blue-100"
                            title="Detail">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>

                        {{-- Tombol Edit (Orange) --}}
                        <button type="button"
                            onclick="openEditModal(
                            '{{ $item->id_pendamping }}',
                            '{{ $item->id_user }}',   
                            '{{ $item->nik }}',
                            '{{ $item->nama_pendamping }}',
                            '{{ $item->jenis_kelamin }}',
                            '{{ $item->no_hp }}',
                            '{{ $item->id_kecamatan }}',
                            '{{ $item->status }}',
                            '{{ $item->tanggal_mulai }}',
                            '{{ $item->tanggal_selesai }}'
                        )"
                            class="p-2 bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white rounded-lg transition-all shadow-sm border border-orange-100"
                            title="Edit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>

                        {{-- Tombol Hapus (Merah) --}}
                        <form action="{{ route('pendamping.delete', $item->id_pendamping) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                                class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm border border-red-100"
                                title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ===================== MODAL DETAIL ===================== --}}
<div id="DetailModal"
    onclick="closeDetailModal()"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">

    <div class="bg-white w-[550px] max-h-[90vh] rounded-xl shadow-lg flex flex-col"
        onclick="event.stopPropagation()">

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h2 class="text-lg font-semibold">Detail Pendamping</h2>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-5 overflow-y-auto flex-1">

            {{-- Loading --}}
            <div id="detail-loading" class="flex justify-center items-center py-10">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </div>

            {{-- Konten --}}
            <div id="detail-content" class="hidden">

                {{-- Foto + Nama + Status --}}
                <div class="flex flex-col items-center mb-6">
                    <div id="detail-foto-wrapper" class="mb-3"></div>
                    <h3 id="detail-nama" class="text-xl font-bold text-gray-800"></h3>
                    <span id="detail-status-badge" class="mt-1 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium"></span>
                </div>

                {{-- Grid info --}}
                <div class="grid grid-cols-2 gap-x-6 gap-y-4">

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">NIK</p>
                        <p id="detail-nik" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Jenis Kelamin</p>
                        <p id="detail-jk" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tempat Lahir</p>
                        <p id="detail-tempat-lahir" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tanggal Lahir</p>
                        <p id="detail-tanggal-lahir" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div class="col-span-2">
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Alamat</p>
                        <p id="detail-alamat" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">No HP</p>
                        <p id="detail-no-hp" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Email</p>
                        <p id="detail-email" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Pendidikan Terakhir</p>
                        <p id="detail-pendidikan" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Kecamatan</p>
                        <p id="detail-kecamatan" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tanggal Mulai</p>
                        <p id="detail-tanggal-mulai" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Tanggal Selesai</p>
                        <p id="detail-tanggal-selesai" class="text-sm text-gray-700 font-medium mt-0.5"></p>
                    </div>

                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="px-5 py-3 border-t flex justify-end">
            <button onclick="closeDetailModal()"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-5 py-2 rounded-lg transition">
                Tutup
            </button>
        </div>

    </div>
</div>

{{-- ===================== MODAL TAMBAH ===================== --}}
<div id="modal"
    onclick="closeModal()"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">

    <div class="bg-white w-[600px] max-h-[90vh] rounded-xl shadow-lg flex flex-col"
        onclick="event.stopPropagation()">

        <div class="px-5 py-3 border-b">
            <h2 class="text-lg font-semibold">Tambah Pendamping</h2>
        </div>

        <form action="{{ route('pendamping.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf

            <div class="p-5 overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-1">Pilih User Pendamping</label>
                        <select name="nama_pendamping" id="select_nama" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none bg-white"
                            onchange="document.getElementById('input_nik').value = this.options[this.selectedIndex].getAttribute('data-nik') || ''; document.getElementById('input_id_user').value = this.options[this.selectedIndex].getAttribute('data-id') || '';">
                            <option value="" disabled selected>-- Pilih Pendamping dari Akun User --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->nama }}" data-nik="{{ $user->nik }}" data-id="{{ $user->id_user }}">{{ $user->nama }}</option>
                            @endforeach {{-- <--- ENDFOREACH HARUSNYA DI SINI --}}
                        </select>

                        {{-- HIDDEN INPUT: Ini yang akan mengirim id_user ke controller --}}
                        <input type="hidden" name="id_user" id="input_id_user">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-1">NIK</label>
                        {{-- Hapus atribut value="{{ $user->nik }}" yang tadi nyangkut --}}
                        <input type="text" id="input_nik" name="nik" class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-600 outline-none cursor-not-allowed" readonly placeholder="Otomatis terisi...">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold mb-1">Jenis Kelamin</label>
                        <div class="flex gap-6 mt-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="jenis_kelamin" value="L" class="accent-blue-600"> Laki-laki
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="jenis_kelamin" value="P" class="accent-pink-500"> Perempuan
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Tempat Lahir</label>
                        <input name="tempat_lahir" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-semibold">Alamat</label>
                        <textarea name="alamat" class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">No HP</label>
                        <input name="no_hp" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Email</label>
                        <input name="email" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Pendidikan</label>
                        <select name="pendidikan_terakhir" class="w-full border rounded-lg px-3 py-2">
                            <option>SMA</option>
                            <option>D3</option>
                            <option>S1</option>
                            <option>S2</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Kecamatan</label>
                        <select name="id_kecamatan" class="w-full border rounded-lg px-3 py-2">
                            @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-semibold">Foto</label>
                        <input type="file" name="foto" class="w-full border rounded-lg px-3 py-2">
                    </div>

                </div>
            </div>

            <div class="p-4 border-t flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="bg-yellow-400 px-4 py-2 rounded-lg">Batal</button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ===================== MODAL EDIT ===================== --}}
<div id="EditModal"
    onclick="closeEditModal()"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">

    <div class="bg-white w-[600px] max-h-[90vh] rounded-xl shadow-lg flex flex-col"
        onclick="event.stopPropagation()">

        <div class="px-5 py-3 border-b">
            <h2 class="text-lg font-semibold">Edit Pendamping</h2>
        </div>

        <form id="formPendamping" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="p-5 overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-4">

                    <div class="col-span-2">
                        <label class="text-sm font-semibold">Nama Pendamping</label>
                        <select id="edit_nama" name="nama_pendamping" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none"
                            onchange="document.getElementById('edit_nik').value = this.options[this.selectedIndex].getAttribute('data-nik') || ''; document.getElementById('edit_id_user').value = this.options[this.selectedIndex].getAttribute('data-id') || '';">
                            <option value="" disabled>-- Pilih Pendamping --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->nama }}" data-nik="{{ $user->nik }}" data-id="{{ $user->id_user }}">{{ $user->nama }}</option>
                            @endforeach
                        </select>

                        {{-- HIDDEN INPUT: Ini yang akan mengirim id_user ke controller --}}
                        <input type="hidden" name="id_user" id="edit_id_user">
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-semibold">NIK</label>
                        <input id="edit_nik" name="nik" type="text" class="w-full border px-3 py-2 rounded bg-gray-100 text-gray-600 outline-none cursor-not-allowed" readonly>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Jenis Kelamin</label>
                        <select id="edit_jk" name="jenis_kelamin" class="w-full border px-3 py-2 rounded">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">No HP</label>
                        <input id="edit_no_hp" name="no_hp" class="w-full border px-3 py-2 rounded">
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Kecamatan</label>
                        <select id="edit_kecamatan" name="id_kecamatan" class="w-full border px-3 py-2 rounded">
                            @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Status</label>
                        <select id="edit_status" name="status" class="w-full border px-3 py-2 rounded">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Tanggal Mulai</label>
                        <input type="date" id="edit_tanggal_mulai" name="tanggal_mulai" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-semibold">Tanggal Selesai</label>
                        <input type="date" id="edit_tanggal_selesai" name="tanggal_selesai" class="w-full border px-3 py-2 rounded focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="text-sm font-semibold">Foto</label>
                        <input type="file" name="foto" class="w-full border px-3 py-2 rounded">
                    </div>

                </div>
            </div>

            <div class="p-4 border-t flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="bg-yellow-400 px-4 py-2 rounded-lg">Batal</button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    // ================= MODAL TAMBAH =================
    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }

    // ================= MODAL DETAIL =================
    function openDetailModal(id) {
        document.getElementById('DetailModal').classList.remove('hidden');
        document.getElementById('detail-loading').classList.remove('hidden');
        document.getElementById('detail-content').classList.add('hidden');

        fetch('/admin/pendamping/' + id)
            .then(response => response.json())
            .then(data => {
                // Foto
                const fotoWrapper = document.getElementById('detail-foto-wrapper');
                if (data.foto) {
                    fotoWrapper.innerHTML = `<img src="/storage/foto_pendamping/${data.foto}"
                    class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">`;
                } else {
                    fotoWrapper.innerHTML = `
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center border-4 border-white shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>`;
                }

                // Nama & status
                document.getElementById('detail-nama').textContent = data.nama_pendamping ?? '-';
                const badge = document.getElementById('detail-status-badge');
                if (data.status === 'Aktif') {
                    badge.textContent = 'Aktif';
                    badge.className = 'mt-1 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                } else {
                    badge.textContent = data.status ?? '-';
                    badge.className = 'mt-1 inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                }

                // Field lain
                document.getElementById('detail-nik').textContent = data.nik ?? '-';
                document.getElementById('detail-jk').textContent = data.jenis_kelamin === 'L' ? 'Laki-laki' : (data.jenis_kelamin === 'P' ? 'Perempuan' : '-');
                document.getElementById('detail-tempat-lahir').textContent = data.tempat_lahir ?? '-';
                document.getElementById('detail-tanggal-lahir').textContent = data.tanggal_lahir ?
                    new Date(data.tanggal_lahir).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    }) :
                    '-';
                document.getElementById('detail-alamat').textContent = data.alamat ?? '-';
                document.getElementById('detail-no-hp').textContent = data.no_hp ?? '-';
                document.getElementById('detail-email').textContent = data.email ?? '-';
                document.getElementById('detail-pendidikan').textContent = data.pendidikan_terakhir ?? '-';
                document.getElementById('detail-kecamatan').textContent = data.kecamatan ? data.kecamatan.nama_kecamatan : '-';
                document.getElementById('detail-tanggal-mulai').textContent = data.tanggal_mulai ? new Date(data.tanggal_mulai).toLocaleDateString('id-ID') : '-';
                document.getElementById('detail-tanggal-selesai').textContent = data.tanggal_selesai ? new Date(data.tanggal_selesai).toLocaleDateString('id-ID') : 'Masih Menjabat';
                document.getElementById('detail-loading').classList.add('hidden');
                document.getElementById('detail-content').classList.remove('hidden');
            })
            .catch(() => {
                document.getElementById('detail-loading').innerHTML =
                    '<p class="text-red-500 text-sm text-center">Gagal memuat data. Silakan coba lagi.</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('DetailModal').classList.add('hidden');
    }

    // ================= MODAL EDIT =================
    // Tambahkan parameter id_user
    function openEditModal(id, id_user, nik, nama, jk, nohp, kecamatan, status, tanggal_mulai, tanggal_selesai) {
        document.getElementById('edit_id_user').value = id_user; // Isi hidden input
        document.getElementById('edit_nik').value = nik;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_jk').value = jk;
        document.getElementById('edit_no_hp').value = nohp;
        document.getElementById('edit_kecamatan').value = kecamatan;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_tanggal_mulai').value = tanggal_mulai;
        document.getElementById('edit_tanggal_selesai').value = tanggal_selesai;

        document.getElementById('formPendamping').action = "/admin/pendamping/" + id;
        document.getElementById('EditModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('EditModal').classList.add('hidden');
    }

    // ================= SEARCH =================
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let keyword = this.value.toLowerCase();
        document.querySelectorAll('.searchable-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });
</script>

@endsection