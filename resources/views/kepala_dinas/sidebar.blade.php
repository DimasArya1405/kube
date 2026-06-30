<nav class="flex-1 px-4 mt-4 space-y-2 overflow-y-auto custom-scrollbar">
    
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group bg-indigo-800 text-white border-l-4 border-white shadow-inner">
        <i data-lucide="pie-chart" class="w-5 h-5 flex-shrink-0"></i>
        <span class="font-medium text-sm text-left w-full">Dashboard Statistik</span>
    </a>

    <div class="relative">
        <button onclick="toggleDropdown('monitorDinas', 'monitorIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="eye" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm text-left">Monitoring Program</span>
            </div>
            <i data-lucide="chevron-down" id="monitorIcon" class="w-4 h-4 transition-transform duration-300 flex-shrink-0"></i>
        </button>
        <div id="monitorDinas" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('kadis.persetujuan_bantuan_kube.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Persetujuan KUBE</a>
            <a href="{{ route('kadis.pencairan_bantuan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Pencairan Bantuan</a>
            <a href="{{ url('/kube') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Sebaran KUBE (Yana)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Penggunaan Bantuan (Noni)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Galeri Kegiatan (Tika)</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('analisisDinas', 'analisisIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="trending-up" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm text-left">Analisis Performa</span>
            </div>
            <i data-lucide="chevron-down" id="analisisIcon" class="w-4 h-4 transition-transform duration-300 flex-shrink-0"></i>
        </button>
        <div id="analisisDinas" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('ranking.kube') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Ranking KUBE (Shela)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Prediksi Kelulusan (Aulia)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Rekap KUBE Aktif/Vakum</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('docDinas', 'docIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="file-check" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm text-left">Arsip & Laporan</span>
            </div>
            <i data-lucide="chevron-down" id="docIcon" class="w-4 h-4 transition-transform duration-300 flex-shrink-0"></i>
        </button>
        <div id="docDinas" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Berita Acara (Probo)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Laporan Keuangan Global</a>
            <a href="{{ route('kadis.laporan.kecamatan') }}"class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Laporan Kecamatan</a>
        </div>
    </div>
    
    <div class="relative">
        <button onclick="toggleDropdown('monevMenu', 'monevIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="activity" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm text-left">Monev & Bimbingan</span>
            </div>
            <i data-lucide="chevron-down" id="monevIcon" class="w-4 h-4 transition-transform duration-300 flex-shrink-0"></i>
        </button>
        <div id="monevMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('monitoring.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Monitoring Bantuan (Noni)</a>
            <a href="{{ route('laporan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Laporan Keuangan (Fassha)</a>
            <a href="{{ route('kunjungan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Kunjungan (Meilita)</a>
            <a href="{{ route('perkembangan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Perkembangan Usaha (Ferina)</a>
            <a href="{{ route('bimbingan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Bimbingan (Shalshabilla)</a>
            <a href="{{ route('pelatihan.index') }}" class="py-2 px-3 text-indigo-200 hover:text-white block text-left w-full">Pelatihan KUBE (Devia)</a>
        </div>
    </div>
</nav>