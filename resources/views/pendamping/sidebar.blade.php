<nav class="flex-1 px-4 mt-4 space-y-2 overflow-y-auto custom-scrollbar">
    <a href="#"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group bg-indigo-800 text-white border-l-4 border-white shadow-inner">
        <i data-lucide="pie-chart" class="w-5 h-5"></i>
        <span class="font-medium">Dashboard Statistik</span>
    </a>

    <div class="relative">
        <button onclick="toggleDropdown('analisisPendampingMenu', 'analisisPendampingIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="trending-up" class="w-5 h-5"></i>
                <span class="font-medium text-sm">Analisis Performa</span>
            </div>
            <i data-lucide="chevron-down" id="analisisPendampingIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>

        <div id="analisisPendampingMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('prediksi.daftar') }}" class="py-2 px-3 text-indigo-200 hover:text-white">
                Prediksi KUBE
            </a>
        </div>
    </div>
</nav>