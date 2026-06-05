<nav class="flex-1 px-4 mt-4 space-y-2 overflow-y-auto custom-scrollbar">
    <a href="#"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group bg-indigo-800 text-white border-l-4 border-white shadow-inner">
        <i data-lucide="layout-grid" class="w-5 h-5"></i>
        <span class="font-medium">Dashboard Wilayah</span>
    </a>

    <div class="relative">
        <button onclick="toggleDropdown('wilayahMenu', 'wilayahIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="map" class="w-5 h-5"></i>
                <span class="font-medium text-start text-sm">Manajemen Wilayah</span>
            </div>
            <i data-lucide="chevron-down" id="wilayahIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="wilayahMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Data Cluster Usaha</a>
            <a href="{{ url('/kube') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Daftar KUBE Binaan</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Data Pendamping</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('supervisiMenu', 'supervisiIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Supervisi</span>
            </div>
            <i data-lucide="chevron-down" id="supervisiIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="supervisiMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Plotting Pendamping</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Rekap Kunjungan Lapangan</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Evaluasi Pembimbingan</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('monBantuanMenu', 'monBantuanIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Monitoring</span>
            </div>
            <i data-lucide="chevron-down" id="monBantuanIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="monBantuanMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Verifikasi Pengajuan</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Progres Pencairan Wilayah</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Laporan Keuangan Kolektif</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('analisisKoordMenu', 'analisisKoordIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="pie-chart" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Laporan Akhir</span>
            </div>
            <i data-lucide="chevron-down" id="analisisKoordIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="analisisKoordMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Ranking KUBE Kecamatan</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Rekap KUBE Aktif/Vakum</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Cetak Berita Acara</a>
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
            <a href="{{ route('perkembangan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Perkembangan Usaha (Ferina)</a>
            <a href="{{ route('bimbingan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Bimbingan (Shalshabilla)</a>
            <a href="{{ route('pelatihan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Pelatihan KUBE (Devia)</a>
        </div>
    </div>
</nav>