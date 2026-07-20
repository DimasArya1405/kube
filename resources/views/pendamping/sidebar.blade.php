@php
    $kube = request()->routeIs('kube.*', 'anggota_kube.*');
    $monev = request()->routeIs('kunjungan.*', 'monitoring.*');
    $bimbingan = request()->routeIs('bimbingan.*');
    $usulan = request()->routeIs('pengajuan.*', 'laporan.*');
    $analisis = request()->routeIs('prediksi.*', 'ranking.kube*');
    $active = 'bg-indigo-800 text-white shadow-inner';
    $inactive = 'text-indigo-100 hover:bg-indigo-600 hover:text-white';
    $subActive = 'bg-indigo-700 text-white border-l-2 border-white';
    $subInactive = 'text-indigo-200 hover:bg-indigo-700/60 hover:text-white';
@endphp
<nav class="flex-1 px-4 mt-4 space-y-2 overflow-y-auto custom-scrollbar">
    <a href="{{ route('pendamping.dashboard') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('pendamping.dashboard') ? 'bg-indigo-800 text-white border-l-4 border-white shadow-inner' : $inactive }}"><i
            data-lucide="layout-dashboard" class="w-5 h-5"></i><span class="font-medium">Dashboard Binaan</span></a>
    <div class="relative"><button type="button" onclick="toggleDropdown('kubeBinaanMenu', 'kubeBinaanIcon')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ $kube ? $active : $inactive }}"><span
                class="flex items-center gap-3"><i data-lucide="users" class="w-5 h-5"></i><span
                    class="font-medium text-sm">Data KUBE Binaan</span></span><i data-lucide="chevron-down"
                id="kubeBinaanIcon" class="w-4 h-4 transition-transform {{ $kube ? 'rotate-180' : '' }}"></i></button>
        <div id="kubeBinaanMenu"
            class="{{ $kube ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('kube.index') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('kube.*') ? $subActive : $subInactive }}">Daftar KUBE
                Binaan</a><a href="{{ route('anggota_kube.index') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('anggota_kube.*') ? $subActive : $subInactive }}">Detail
                Anggota Kelompok</a></div>
    </div>
    <div class="relative"><button type="button" onclick="toggleDropdown('monevMenu', 'monevIcon')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ $monev ? $active : $inactive }}"><span
                class="flex items-center gap-3"><i data-lucide="activity" class="w-5 h-5"></i><span
                    class="font-medium text-sm">Monev & Bimbingan</span></span><i data-lucide="chevron-down"
                id="monevIcon" class="w-4 h-4 transition-transform {{ $monev ? 'rotate-180' : '' }}"></i></button>
        <div id="monevMenu"
            class="{{ $monev ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('kunjungan.index') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('kunjungan.*') ? $subActive : $subInactive }}">Jadwal
                Kunjungan Pendamping</a><a href="{{ route('monitoring.index') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('monitoring.*') ? $subActive : $subInactive }}">Monitoring
                Bantuan</a></div>
    </div>
    <div class="relative"><button type="button" onclick="toggleDropdown('bimbinganMenu', 'bimbinganIcon')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ $bimbingan ? $active : $inactive }}"><span
                class="flex items-center gap-3"><i data-lucide="clipboard-edit" class="w-5 h-5"></i><span
                    class="font-medium text-sm">Bimbingan Lapangan</span></span><i data-lucide="chevron-down"
                id="bimbinganIcon"
                class="w-4 h-4 transition-transform {{ $bimbingan ? 'rotate-180' : '' }}"></i></button>
        <div id="bimbinganMenu"
            class="{{ $bimbingan ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('bimbingan.create') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('bimbingan.create') ? $subActive : $subInactive }}">Input
                Laporan Kunjungan</a><a href="{{ route('bimbingan.index') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('bimbingan.*') ? $subActive : $subInactive }}">Riwayat
                Bimbingan</a></div>
    </div>
    <div class="relative"><button type="button" onclick="toggleDropdown('usulanMenu', 'usulanIcon')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ $usulan ? $active : $inactive }}"><span
                class="flex items-center gap-3"><i data-lucide="file-check-2" class="w-5 h-5"></i><span
                    class="font-medium text-sm">Bantuan & Usulan</span></span><i data-lucide="chevron-down"
                id="usulanIcon" class="w-4 h-4 transition-transform {{ $usulan ? 'rotate-180' : '' }}"></i></button>
        <div id="usulanMenu"
            class="{{ $usulan ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('pengajuan.index') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('pengajuan.*') ? $subActive : $subInactive }}">Status
                Usulan Bantuan</a><a href="{{ route('laporan.index') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('laporan.*') ? $subActive : $subInactive }}">Rekap
                Laporan Bulanan</a></div>
    </div>
    <div class="relative"><button type="button"
            onclick="toggleDropdown('analisisPendampingMenu', 'analisisPendampingIcon')"
            class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all {{ $analisis ? $active : $inactive }}"><span
                class="flex items-center gap-3"><i data-lucide="bar-chart-2" class="w-5 h-5"></i><span
                    class="font-medium text-sm">Analisis Performa</span></span><i data-lucide="chevron-down"
                id="analisisPendampingIcon"
                class="w-4 h-4 transition-transform {{ $analisis ? 'rotate-180' : '' }}"></i></button>
        <div id="analisisPendampingMenu"
            class="{{ $analisis ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('prediksi.daftar') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('prediksi.*') ? $subActive : $subInactive }}">Prediksi
                KUBE</a><a href="{{ route('ranking.kube') }}"
                class="py-2 px-3 rounded-lg {{ request()->routeIs('ranking.kube*') ? $subActive : $subInactive }}">Ranking
                KUBE</a></div>
    </div>
</nav>
