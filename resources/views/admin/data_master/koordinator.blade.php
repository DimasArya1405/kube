{{-- KATRINA --}}
@extends('admin.layout')

@section('title', 'Data Koordinator - KUBE')

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Koordinator</h2>
        <p class="text-gray-500 mt-1">Kelola seluruh data koordinator KUBE.</p>
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div class="flex gap-4 mb-6">
    <div class="bg-orange-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Koordinator Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $koordinator->where('status','aktif')->count() }}</p>
    </div>
    <div class="bg-green-300 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Koordinator Non-Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $koordinator->where('status','non-aktif')->count() }}</p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">

    {{-- Search --}}
    <div class="relative flex-1 min-w-[200px]">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
            </svg>
        </span>
        <input type="text" id="searchInput" placeholder="Cari nama, NIK, kecamatan..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    {{-- Filter Status --}}
    <select id="filterStatus" onchange="filterByStatus(this.value)"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
    </select>

    {{-- Ekspor PDF --}}
    <a href="{{ route('koordinator.export.pdf') }}{{ request('status') ? '?status='.request('status') : '' }}"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        Ekspor PDF
    </a>

    {{-- Ekspor Excel --}}
    <a href="{{ route('koordinator.export.excel') }}{{ request('status') ? '?status='.request('status') : '' }}"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        Ekspor Excel
    </a>

    {{-- Tambah --}}
    <button onclick="openTambahModal()"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + Tambah Koor
    </button>

</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">NIK</th>
                    <th class="px-4 py-3">No HP</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Kesediaan Wilayah</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($koordinator as $item)
                <tr class="border-t border-gray-100 hover:bg-gray-50 searchable-row">
                    <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->user->nama ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->user->nik ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->user->no_hp ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->user->alamat ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($item->status == 'aktif')
                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>
                        @else
                        <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">Non-Aktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">

                            {{-- Detail --}}
                            <button
                                data-id="{{ $item->id_koor }}"
                                data-nama="{{ $item->user->nama ?? '-' }}"
                                data-nik="{{ $item->user->nik ?? '-' }}"
                                data-nohp="{{ $item->user->no_hp ?? '-' }}"
                                data-alamat="{{ $item->user->alamat ?? '-' }}"
                                data-kecamatan="{{ $item->kecamatan->nama_kecamatan ?? '-' }}"
                                data-status="{{ $item->status }}"
                                onclick="openDetailModal(this)"
                                class="text-blue-500 hover:text-blue-700" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Edit --}}
                            <button
                                data-id="{{ $item->id_koor }}"
                                data-nama="{{ $item->user->nama ?? '-' }}"
                                data-kecamatan="{{ $item->id_kecamatan }}"
                                data-status="{{ $item->status }}"
                                onclick="openEditModal(this)"
                                class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            {{-- Hapus --}}
                            <form action="{{ route('koordinator.delete', $item->id_koor) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">Belum ada data koordinator.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- ==================== MODAL TAMBAH (Step 1: Pilih User) ==================== --}}
<div id="modal-tambah-koor" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">
    <div class="relative p-4 w-full max-w-2xl">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Koordinator</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Pilih user koordinator yang belum diproses, review datanya, lalu jadikan koordinator.</p>
                </div>
                <button onclick="closeTambahModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>

            {{-- Daftar calon koordinator --}}
            <div class="p-5">
                @if($users->isEmpty())
                    <p class="text-center text-gray-400 py-6">Tidak ada user koordinator yang belum diproses.</p>
                @else
                    <p class="text-sm text-gray-500 mb-3">Klik <strong>Lihat Detail</strong> untuk review data sebelum menjadikan koordinator.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2">Nama</th>
                                    <th class="px-3 py-2">NIK</th>
                                    <th class="px-3 py-2">No HP</th>
                                    <th class="px-3 py-2">Alamat</th>
                                    <th class="px-3 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-t border-gray-100 hover:bg-gray-50">
                                    <td class="px-3 py-2 font-medium text-gray-800">{{ $user->nama }}</td>
                                    <td class="px-3 py-2">{{ $user->nik ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $user->no_hp ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $user->alamat ?? '-' }}</td>
                                    <td class="px-3 py-2">
                                        <button
                                            data-id="{{ $user->id_user }}"
                                            data-nama="{{ $user->nama }}"
                                            data-nik="{{ $user->nik ?? '-' }}"
                                            data-nohp="{{ $user->no_hp ?? '-' }}"
                                            data-email="{{ $user->email ?? '-' }}"
                                            data-alamat="{{ $user->alamat ?? '-' }}"
                                            onclick="openPreviewModal(this)"
                                            class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg whitespace-nowrap">
                                            Lihat Detail
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="flex justify-end p-4 border-t">
                <button onclick="closeTambahModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">Tutup</button>
            </div>
        </div>
    </div>
</div>


{{-- ==================== MODAL PREVIEW USER (Step 2: Review + Pilih Kecamatan) ==================== --}}
<div id="modal-preview-user" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[60] flex justify-center items-center w-full h-full bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Detail Calon Koordinator</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Review data user, lalu tentukan kesediaan wilayahnya.</p>
                </div>
                <button onclick="closePreviewModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>

            {{-- Info User --}}
            <div class="px-5 pt-5 pb-3">
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <p class="text-xs text-gray-400 uppercase font-semibold mb-3 tracking-wide">Data User</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Nama</p>
                            <p class="text-sm font-semibold text-gray-800" id="preview-nama"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">NIK</p>
                            <p class="text-sm text-gray-700" id="preview-nik"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">No HP</p>
                            <p class="text-sm text-gray-700" id="preview-nohp"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Email</p>
                            <p class="text-sm text-gray-700" id="preview-email"></p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-gray-400 mb-0.5">Alamat</p>
                            <p class="text-sm text-gray-700" id="preview-alamat"></p>
                        </div>
                    </div>
                </div>

                {{-- Form Pilih Kecamatan --}}
                <form id="form-tambah-koor" action="{{ route('koordinator.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_user" id="preview-id-user">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Kesediaan Wilayah (Kecamatan)
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="id_kecamatan" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">* Status akan otomatis <strong>Non-Aktif</strong> hingga koordinator diberi tugas.</p>
                    </div>
                </form>
            </div>

            <div class="flex justify-between items-center p-4 border-t">
                <button onclick="closePreviewModal()"
                    class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
                    ← Kembali ke daftar
                </button>
                <button onclick="submitTambahKoor()"
                    class="bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-5 py-2 rounded-lg">
                    ✓ Jadikan Koordinator
                </button>
            </div>
        </div>
    </div>
</div>


{{-- ==================== MODAL DETAIL KOORDINATOR ==================== --}}
<div id="modal-detail-koor" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">
    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Detail Koordinator</h3>
                <button onclick="closeDetailModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>
            <div class="p-5 space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Nama</p>
                        <p class="text-sm font-semibold text-gray-800" id="detail-nama"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">NIK</p>
                        <p class="text-sm text-gray-700" id="detail-nik"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">No HP</p>
                        <p class="text-sm text-gray-700" id="detail-nohp"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kesediaan Wilayah</p>
                        <p class="text-sm text-gray-700" id="detail-kecamatan"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Status</p>
                        <p class="text-sm" id="detail-status"></p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-400 mb-1">Alamat</p>
                    <p class="text-sm text-gray-700" id="detail-alamat"></p>
                </div>
            </div>
            <div class="flex justify-end p-4 border-t">
                <button onclick="closeDetailModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">Tutup</button>
            </div>
        </div>
    </div>
</div>


{{-- ==================== MODAL EDIT KOORDINATOR ==================== --}}
<div id="modal-edit-koor" tabindex="-1"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">
    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Edit Data Koordinator</h3>
                    <p class="text-xs text-gray-400 mt-0.5" id="edit-nama-label"></p>
                </div>
                <button onclick="closeEditModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">✕</button>
            </div>
            <form id="form-edit-koor" method="POST" class="p-5">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kesediaan Wilayah (Kecamatan)</label>
                    <select name="id_kecamatan" id="edit-kecamatan" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="edit-status" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                    </select>
                    <p class="text-xs text-gray-400 mt-1">* Status aktif biasanya diubah otomatis oleh fitur pembagian koordinator.</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-400 hover:bg-gray-500 text-white text-sm font-medium px-5 py-2 rounded-lg">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- SCRIPT --}}
<script>
    // ── SEARCH ──
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.searchable-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });

    // ── FILTER STATUS ──
    function filterByStatus(status) {
        const url = new URL(window.location.href);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    }

    // ── MODAL TAMBAH (Step 1) ──
    function openTambahModal() {
        document.getElementById('modal-tambah-koor').classList.remove('hidden');
    }
    function closeTambahModal() {
        document.getElementById('modal-tambah-koor').classList.add('hidden');
    }

    // ── MODAL PREVIEW USER (Step 2) ──
    function openPreviewModal(btn) {
        document.getElementById('preview-id-user').value          = btn.dataset.id;
        document.getElementById('preview-nama').textContent       = btn.dataset.nama;
        document.getElementById('preview-nik').textContent        = btn.dataset.nik;
        document.getElementById('preview-nohp').textContent       = btn.dataset.nohp;
        document.getElementById('preview-email').textContent      = btn.dataset.email;
        document.getElementById('preview-alamat').textContent     = btn.dataset.alamat;

        // Reset pilihan kecamatan
        document.querySelector('#form-tambah-koor select[name="id_kecamatan"]').value = '';

        document.getElementById('modal-preview-user').classList.remove('hidden');
    }
    function closePreviewModal() {
        document.getElementById('modal-preview-user').classList.add('hidden');
    }

    function submitTambahKoor() {
        const kecamatan = document.querySelector('#form-tambah-koor select[name="id_kecamatan"]').value;
        if (!kecamatan) {
            alert('Pilih kecamatan kesediaan terlebih dahulu!');
            return;
        }
        document.getElementById('form-tambah-koor').submit();
    }

    // ── MODAL DETAIL ──
    function openDetailModal(btn) {
        document.getElementById('detail-nama').textContent      = btn.dataset.nama;
        document.getElementById('detail-nik').textContent       = btn.dataset.nik;
        document.getElementById('detail-nohp').textContent      = btn.dataset.nohp;
        document.getElementById('detail-alamat').textContent    = btn.dataset.alamat;
        document.getElementById('detail-kecamatan').textContent = btn.dataset.kecamatan;

        const statusEl = document.getElementById('detail-status');
        if (btn.dataset.status === 'aktif') {
            statusEl.innerHTML = '<span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">Aktif</span>';
        } else {
            statusEl.innerHTML = '<span class="bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1 rounded-full">Non-Aktif</span>';
        }

        document.getElementById('modal-detail-koor').classList.remove('hidden');
    }
    function closeDetailModal() {
        document.getElementById('modal-detail-koor').classList.add('hidden');
    }

    // ── MODAL EDIT ──
    function openEditModal(btn) {
        document.getElementById('form-edit-koor').action      = `/admin/koordinator/${btn.dataset.id}`;
        document.getElementById('edit-nama-label').textContent = 'Koordinator: ' + btn.dataset.nama;
        document.getElementById('edit-kecamatan').value        = btn.dataset.kecamatan;
        document.getElementById('edit-status').value           = btn.dataset.status;
        document.getElementById('modal-edit-koor').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modal-edit-koor').classList.add('hidden');
    }
</script>

@stop