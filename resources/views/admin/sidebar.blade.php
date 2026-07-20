@php
    $isMaster = request()->routeIs('admin.users*', 'kube.*', 'anggota_kube.*', 'pendamping.*', 'koordinator.*', 'kategorikube.*', 'cluster_usaha.*', 'admin.alur_bantuan.jenis_bantuan.*');
    $isTask = request()->routeIs('pembagian_pendamping.*', 'pembagian_koordinator.*');
    $isBantuan = request()->routeIs('pengajuan.*', 'admin.pengajuan_bantuan_baru.*', 'admin.persetujuan_bantuan_kube.*', 'admin.pencairan_bantuan.*', 'mitra.*', 'bantuan.*', 'kolaborasi.*', 'penyaluran.*');
    $isMonev = request()->routeIs('monitoring.*', 'laporan.index', 'laporan.store', 'laporan.update', 'laporan.destroy', 'laporan.export.*', 'admin.kunjungan.*', 'perkembangan.*', 'bimbingan.*', 'pelatihan.*');
    $isAnalisis = request()->routeIs('admin.prediksi-kube.*', 'ranking.kube*');
    $isReport = request()->routeIs('laporan.kecamatan*', 'galeri.*', 'rekap_kube.*');
    $dropdownActive = 'bg-indigo-800 text-white shadow-inner';
    $dropdownInactive = 'text-indigo-100 hover:bg-indigo-600 hover:text-white';
    $linkActive = 'bg-indigo-700 text-white border-l-2 border-white';
    $linkInactive = 'text-indigo-200 hover:bg-indigo-700/60 hover:text-white';
@endphp

<nav class="flex-1 px-4 mt-4 space-y-2 overflow-y-auto custom-scrollbar">
    <a href="{{ route('admin.dashboard') }}"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800 text-white border-l-4 border-white shadow-inner' : $dropdownInactive }}">
        <i data-lucide="layout-grid" class="w-5 h-5"></i>
        <span class="font-medium">Dashboard</span>
    </a>

    <div class="relative">
        <button type="button" onclick="toggleDropdown('masterMenu', 'masterIcon')" aria-expanded="{{ $isMaster ? 'true' : 'false' }}" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ $isMaster ? $dropdownActive : $dropdownInactive }}">
            <span class="flex items-center gap-3"><i data-lucide="database" class="w-5 h-5"></i><span class="font-medium text-sm">Data Master</span></span>
            <i data-lucide="chevron-down" id="masterIcon" class="w-4 h-4 transition-transform duration-300 {{ $isMaster ? 'rotate-180' : '' }}"></i>
        </button>
        <div id="masterMenu" class="{{ $isMaster ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('admin.users') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('admin.users*') ? $linkActive : $linkInactive }}">Data User (Zahran)</a>
            <a href="{{ route('kube.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('kube.*') ? $linkActive : $linkInactive }}">Data KUBE (Yana)</a>
            <a href="{{ route('anggota_kube.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('anggota_kube.*') ? $linkActive : $linkInactive }}">Data Anggota (Yana)</a>
            <a href="{{ route('pendamping.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('pendamping.index', 'pendamping.show', 'pendamping.edit') ? $linkActive : $linkInactive }}">Data Pendamping (Tiara)</a>
            <a href="{{ route('koordinator.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('koordinator.*') ? $linkActive : $linkInactive }}">Data Koordinator (Katrina)</a>
            <a href="{{ route('kategorikube.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('kategorikube.*') ? $linkActive : $linkInactive }}">Kategori KUBE (Tika)</a>
            <a href="{{ route('cluster_usaha.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('cluster_usaha.*') ? $linkActive : $linkInactive }}">Cluster (Ana)</a>
            <a href="{{ route('admin.alur_bantuan.jenis_bantuan.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('admin.alur_bantuan.jenis_bantuan.*') ? $linkActive : $linkInactive }}">Jenis Bantuan (Dimas)</a>
        </div>
    </div>

    <div class="relative">
        <button type="button" onclick="toggleDropdown('taskMenu', 'taskIcon')" aria-expanded="{{ $isTask ? 'true' : 'false' }}" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ $isTask ? $dropdownActive : $dropdownInactive }}">
            <span class="flex items-center gap-3"><i data-lucide="map-pin" class="w-5 h-5"></i><span class="font-medium text-sm">Penugasan</span></span><i data-lucide="chevron-down" id="taskIcon" class="w-4 h-4 transition-transform duration-300 {{ $isTask ? 'rotate-180' : '' }}"></i>
        </button>
        <div id="taskMenu" class="{{ $isTask ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('pembagian_pendamping.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('pembagian_pendamping.*') ? $linkActive : $linkInactive }}">Pembagian Pendamping (Yana)</a>
            <a href="{{ route('pembagian_koordinator.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('pembagian_koordinator.*') ? $linkActive : $linkInactive }}">Pembagian Koordinator (Ana)</a>
        </div>
    </div>

    <div class="relative">
        <button type="button" onclick="toggleDropdown('bantuanMenu', 'bantuanIcon')" aria-expanded="{{ $isBantuan ? 'true' : 'false' }}" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ $isBantuan ? $dropdownActive : $dropdownInactive }}">
            <span class="flex items-center gap-3"><i data-lucide="clipboard-check" class="w-5 h-5"></i><span class="font-medium text-sm">Alur Bantuan</span></span><i data-lucide="chevron-down" id="bantuanIcon" class="w-4 h-4 transition-transform duration-300 {{ $isBantuan ? 'rotate-180' : '' }}"></i>
        </button>
        <div id="bantuanMenu" class="{{ $isBantuan ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('pengajuan.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('pengajuan.*') ? $linkActive : $linkInactive }}">Pengajuan KUBE (Putri)</a>
            <a href="{{ route('admin.pengajuan_bantuan_baru.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('admin.pengajuan_bantuan_baru.*') ? $linkActive : $linkInactive }}">Pengajuan Bantuan Baru</a>
            <a href="{{ route('admin.persetujuan_bantuan_kube.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('admin.persetujuan_bantuan_kube.*') ? $linkActive : $linkInactive }}">Persetujuan (Probo)</a>
            <a href="{{ route('admin.pencairan_bantuan.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('admin.pencairan_bantuan.*') ? $linkActive : $linkInactive }}">Tahap Pencairan (Dimas)</a>
            <a href="{{ route('mitra.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('mitra.*', 'bantuan.*', 'kolaborasi.*', 'penyaluran.*') ? $linkActive : $linkInactive }}">Mitra & Kolaborasi (Amel)</a>
        </div>
    </div>

    <div class="relative">
        <button type="button" onclick="toggleDropdown('monevMenu', 'monevIcon')" aria-expanded="{{ $isMonev ? 'true' : 'false' }}" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ $isMonev ? $dropdownActive : $dropdownInactive }}">
            <span class="flex items-center gap-3"><i data-lucide="activity" class="w-5 h-5"></i><span class="font-medium text-sm">Monev & Bimbingan</span></span><i data-lucide="chevron-down" id="monevIcon" class="w-4 h-4 transition-transform duration-300 {{ $isMonev ? 'rotate-180' : '' }}"></i>
        </button>
        <div id="monevMenu" class="{{ $isMonev ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('monitoring.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('monitoring.*') ? $linkActive : $linkInactive }}">Monitoring Bantuan (Noni)</a>
            <a href="{{ route('laporan.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('laporan.index', 'laporan.store', 'laporan.update', 'laporan.destroy', 'laporan.export.*') ? $linkActive : $linkInactive }}">Laporan Keuangan (Fassha)</a>
            <a href="{{ route('admin.kunjungan.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('admin.kunjungan.*') ? $linkActive : $linkInactive }}">Jadwal Kunjungan Pendamping (Meilita)</a>
            <a href="{{ route('perkembangan.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('perkembangan.*') ? $linkActive : $linkInactive }}">Perkembangan Usaha (Ferina)</a>
            <a href="{{ route('bimbingan.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('bimbingan.*') ? $linkActive : $linkInactive }}">Bimbingan (Shalshabilla)</a>
            <a href="{{ route('pelatihan.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('pelatihan.*') ? $linkActive : $linkInactive }}">Pelatihan KUBE (Devia)</a>
        </div>
    </div>

    <div class="relative">
        <button type="button" onclick="toggleDropdown('analisisMenu', 'analisisIcon')" aria-expanded="{{ $isAnalisis ? 'true' : 'false' }}" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ $isAnalisis ? $dropdownActive : $dropdownInactive }}">
            <span class="flex items-center gap-3"><i data-lucide="bar-chart-3" class="w-5 h-5"></i><span class="font-medium text-sm">Analisis Akreditasi</span></span><i data-lucide="chevron-down" id="analisisIcon" class="w-4 h-4 transition-transform duration-300 {{ $isAnalisis ? 'rotate-180' : '' }}"></i>
        </button>
        <div id="analisisMenu" class="{{ $isAnalisis ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('admin.prediksi-kube.daftar') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('admin.prediksi-kube.*') ? $linkActive : $linkInactive }}">Prediksi KUBE (Aulia)</a>
            <a href="{{ route('ranking.kube') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('ranking.kube*') ? $linkActive : $linkInactive }}">Ranking KUBE (Shela)</a>
        </div>
    </div>

    <div class="relative">
        <button type="button" onclick="toggleDropdown('reportMenu', 'reportIcon')" aria-expanded="{{ $isReport ? 'true' : 'false' }}" class="w-full flex items-center justify-between px-4 py-3 rounded-xl transition-all group {{ $isReport ? $dropdownActive : $dropdownInactive }}">
            <span class="flex items-center gap-3"><i data-lucide="file-text" class="w-5 h-5"></i><span class="font-medium text-sm">Laporan Akhir</span></span><i data-lucide="chevron-down" id="reportIcon" class="w-4 h-4 transition-transform duration-300 {{ $isReport ? 'rotate-180' : '' }}"></i>
        </button>
        <div id="reportMenu" class="{{ $isReport ? '' : 'hidden' }} flex flex-col mt-2 ml-4 space-y-1 border-l border-indigo-500/50 pl-4 text-[11px] uppercase tracking-widest font-bold">
            <a href="{{ route('laporan.kecamatan') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('laporan.kecamatan*') ? $linkActive : $linkInactive }}">Laporan Kecamatan (Alva)</a>
            <a href="{{ route('galeri.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('galeri.*') ? $linkActive : $linkInactive }}">Galeri Kegiatan (Tika)</a>
            <a href="{{ route('rekap_kube.index') }}" class="py-2 px-3 rounded-lg {{ request()->routeIs('rekap_kube.*') ? $linkActive : $linkInactive }}">Rekap KUBE (Fia)</a>
        </div>
    </div>

    <a href="{{ route('template-laporan.index') }}" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('template-laporan.*') ? $dropdownActive : $dropdownInactive }}">
        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i><span class="font-medium text-sm">Template Laporan</span>
    </a>
</nav>
