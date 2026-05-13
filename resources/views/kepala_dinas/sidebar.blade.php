<nav class="flex-1 px-4 mt-4 space-y-2 overflow-y-auto custom-scrollbar">
    <a href="#"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group bg-indigo-800 text-white border-l-4 border-white shadow-inner">
        <i data-lucide="pie-chart" class="w-5 h-5"></i>
        <span class="font-medium">Dashboard Statistik</span>
    </a>
    <a href="{{route('kadis.persetujuan_bantuan_kube.index')}}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group bg-indigo-800 text-white border-l-4 border-white shadow-inner">
        <i class="w-5 h-5 " data-lucide="check"></i>
        <span class="font-medium">Persetujuan Kube</span>
    </a>
    <a href="{{route('kadis.pencairan_bantuan.index')}}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group bg-indigo-800 text-white border-l-4 border-white shadow-inner">
        <i class="w-5 h-5 " data-lucide="dollar-sign"></i>
        <span class="font-medium">Pencairan Bantuan</span>
    </a>

    <div class="relative">
        <button onclick="toggleDropdown('monitorDinas', 'monitorIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="eye" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Monitoring Program</span>
            </div>
            <i data-lucide="chevron-down" id="monitorIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="monitorDinas" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Sebaran KUBE (Alva)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Progres Pencairan (Dimas)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Penggunaan Bantuan (Noni)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Galeri Kegiatan (Tika)</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('analisisDinas', 'analisisIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Analisis Performa</span>
            </div>
            <i data-lucide="chevron-down" id="analisisIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="analisisDinas" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Ranking Kecamatan (Shela)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Prediksi Kelulusan (Aulia)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Rekap KUBE Aktif/Vakum</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('docDinas', 'docIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="file-check" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Arsip & Laporan</span>
            </div>
            <i data-lucide="chevron-down" id="docIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="docDinas" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Berita Acara (Probo)</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Laporan Keuangan Global</a>
        </div>
    </div>
</nav>