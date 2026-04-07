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
                <span class="font-medium text-sm">Data Master</span>
            </div>
            <i data-lucide="chevron-down" id="masterIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="masterMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('admin.users') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Data User (Zahran)</a>
            <a href="{{ url('/kube') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Data KUBE (Yana)</a>
            <a href="{{ url('/anggota_kube') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Data Anggota (Yana)</a>
            <a href="{{ route('pendamping.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Data Pendamping (Tiara)</a>
            <a href="{{ route('koordinator.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Data Koordinator (Katrina)</a>
            <a href="{{ route('kategorikube.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Kategori KUBE (Tika)</a>
            <a href="{{ route('cluster_usaha.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Cluster (Ana)</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('taskMenu', 'taskIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Penugasan</span>
            </div>
            <i data-lucide="chevron-down" id="taskIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="taskMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ url('/pembagian_pendamping') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Pembagian Pendamping (Yana)</a>
            <a href="{{ route('pembagian_koordinator.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Pembagian Koordinator (Ana)</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('bantuanMenu', 'bantuanIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Alur Bantuan</span>
            </div>
            <i data-lucide="chevron-down" id="bantuanIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="bantuanMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('pengajuan.create') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Pengajuan KUBE (Putri)</a>
            <a href="{{route('admin.persetujuan_bantuan_kube.index')}}" class="py-2 px-3 text-indigo-200 hover:text-white">Persetujuan (Probo)</a>
            <a href="{{route('admin.pencairan_bantuan.index')}}" class="py-2 px-3 text-indigo-200 hover:text-white">Tahap Pencairan (Dimas)</a>
            <a href="/admin/mitra" class="py-2 px-3 text-indigo-200 hover:text-white">Mitra & Kolaborasi (Amel)</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('monevMenu', 'monevIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="activity" class="w-5 h-5"></i>
                <span class="font-medium text-sm text-start">Monev & Bimbingan</span>
            </div>
            <i data-lucide="chevron-down" id="monevIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="monevMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[12px] text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('monitoring.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Monitoring Bantuan (Noni)</a>
            <a href="{{ route('laporan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Laporan Keuangan (Fassha)</a>
            <a href="{{ route('kunjungan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Kunjungan (Meilita)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Bimbingan (Shalshabilla)</a>
            <a href="{{ route('pelatihan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Pelatihan KUBE (Devia)</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('analisisMenu', 'analisisIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                <span class="font-medium text-sm text-start">Analisis Akreditasi</span>
            </div>
            <i data-lucide="chevron-down" id="analisisIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="analisisMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Prediksi KUBE (Aulia)</a>
            <a href="{{ route('ranking.kube') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Ranking KUBE (Shela)</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('reportMenu', 'reportIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Laporan Akhir</span>
            </div>
            <i data-lucide="chevron-down" id="reportIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="reportMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('laporan.kecamatan') }}" 
   class="py-2 px-3 text-indigo-200 hover:text-white">
    Laporan Kecamatan (Alva)
</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Galeri Kegiatan (Tika)</a>
            <a href="{{ route('rekap_kube.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Rekap KUBE (Fia)</a>
        </div>
    </div>
</nav>