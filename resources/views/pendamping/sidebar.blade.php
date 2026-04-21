<nav class="flex-1 px-4 mt-4 space-y-2 overflow-y-auto custom-scrollbar">
    <a href="{{ route('pendamping.dashboard') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group bg-indigo-800 text-white border-l-4 border-white shadow-inner">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        <span class="font-medium">Dashboard Binaan</span>
    </a>

    <div class="relative">
        <button onclick="toggleDropdown('kubeBinaanMenu', 'kubeBinaanIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="font-medium text-start text-sm">Data KUBE Binaan</span>
            </div>
            <i data-lucide="chevron-down" id="kubeBinaanIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="kubeBinaanMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ url('/kube') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Daftar KUBE Binaan</a>
            <a href="{{ url('/anggota') }}" class="py-2 px-3 text-indigo-200 hover:text-white">Detail Anggota Kelompok</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('bimbinganMenu', 'bimbinganIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="clipboard-edit" class="w-5 h-5"></i>
                <span class="font-medium text-start text-sm">Bimbingan Lapangan</span>
            </div>
            <i data-lucide="chevron-down" id="bimbinganIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="bimbinganMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Input Laporan Kunjungan</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Riwayat Bimbingan (Monev)</a>
        </div>
    </div>

    <div class="relative">
        <button onclick="toggleDropdown('usulanMenu', 'usulanIcon')" class="w-full flex items-center justify-between px-4 py-3 hover:bg-indigo-600 rounded-xl transition-all text-indigo-100 hover:text-white group">
            <div class="flex items-center gap-3">
                <i data-lucide="file-check-2" class="w-5 h-5"></i>
                <span class="font-medium text-start text-sm">Bantuan & Usulan</span>
            </div>
            <i data-lucide="chevron-down" id="usulanIcon" class="w-4 h-4 transition-transform duration-300"></i>
        </button>
        <div id="usulanMenu" class="hidden flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Status Usulan Bantuan</a>
            <a href="#" class="py-2 px-3 text-indigo-200 hover:text-white">Rekap Laporan Bulanan</a>
        </div>
    </div>
</nav>