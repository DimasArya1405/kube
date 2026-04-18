<nav class="flex-1 px-4 mt-4 space-y-2 overflow-y-auto custom-scrollbar">
    <a href="{{ route('admin.dashboard') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group
       {{ request()->routeIs('admin.dashboard') 
          ? 'bg-indigo-800 text-white border-l-4 border-white shadow-inner' 
          : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }}">
        <i data-lucide="layout-grid" class="w-5 h-5"></i>
        <span class="font-medium">Dashboard</span>
    </a>

    <div class="relative">
        <button onclick="toggleDropdown('masterMenu', 'masterIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="database" class="w-5 h-5"></i>
                <span class="font-medium text-start text-sm">Manajemen Internal</span>
            </div>
            <i data-lucide="chevron-down" id="masterIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="masterMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="" class="py-2 px-3 text-indigo-200 hover:text-white">Data Anggota</a>
            <a href="" class="py-2 px-3 text-indigo-200 hover:text-white">Laporan Keuangan</a>
            <a href="" class="py-2 px-3 text-indigo-200 hover:text-white">Dokumentasi Kegiatan</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('taskMenu', 'taskIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
                <span class="font-medium text-start text-sm">Bantuan dan Pengajuan</span>
            </div>
            <i data-lucide="chevron-down" id="taskIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="taskMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="" class="py-2 px-3 text-indigo-200 hover:text-white">Pengajuan Bantuan</a>
            <a href="" class="py-2 px-3 text-indigo-200 hover:text-white">Pencairan Bantuan</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('bantuanMenu', 'bantuanIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Pembinaan</span>
            </div>
            <i data-lucide="chevron-down" id="bantuanIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="bantuanMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('pengajuan.create') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Jadwal Bimbingan</a>
            <a href="{{route('admin.persetujuan_bantuan_kube.index')}}" class="py-2 px-3 text-indigo-200 hover:text-white">Log Kunjungan</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('monevMenu', 'monevIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="activity" class="w-5 h-5"></i>
                <span class="font-medium text-sm text-start">Hasil dan Evaluasi</span>
            </div>
            <i data-lucide="chevron-down" id="monevIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="monevMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[12px] text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('monitoring.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Laporan</a>
            <a href="{{ route('laporan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Rangkin</a>
            <a href="{{ route('kunjungan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Prediksi KUBE</a>
        </div>
    </div>
</nav>