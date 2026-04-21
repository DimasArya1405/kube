<?php

namespace App\Exports;

use App\Models\Kube;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Biar kolom excelnya otomatis ngelebar

class KubeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // Tarik semua relasi "Kereta Api"
        return Kube::with([
            'desa.kecamatan', 
            'clusterUsaha.kategori',
            'pembagianPendamping.pendamping.pembagianKoordinator.koordinator',
            'pembagianPendamping.pembagianKoordinator.koordinator',
            'anggota'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama KUBE',
            'Kategori',
            'Cluster Usaha',
            'Kecamatan',
            'Desa / Kelurahan',
            'Nama Koordinator',
            'Nama Pendamping',
            'Nama Ketua KUBE', // Kita ambil nama ketuanya aja
            'Jumlah Anggota',  // Total anggotanya
            'Status',
            'Tanggal Dibentuk',
            'Keterangan'
        ];
    }

    public function map($kube): array
    {
        static $no = 1;

        // Cari siapa yang jabatannya 'Ketua' di KUBE ini
        $ketua = $kube->anggota->where('jabatan', 'Ketua')->first();

        return [
            $no++,
            $kube->nama_kube,
            $kube->clusterUsaha->kategori->nama_kategori ?? '-',
            $kube->clusterUsaha->nama_cluster ?? '-',
            $kube->desa->kecamatan->nama_kecamatan ?? '-',
            $kube->desa->nama_desa_kelurahan ?? '-',
            $kube->pembagianPendamping->pembagianKoordinator->koordinator->nama_koor ?? 'Belum Ada',
            $kube->pembagianPendamping->pendamping->nama_pendamping ?? 'Belum Ada',
            $ketua ? $ketua->nama_anggota : 'Belum Ada Ketua',
            $kube->anggota->count() . ' Orang', // Ngitung jumlah anggota otomatis
            $kube->status,
            $kube->tanggal_terbentuk,
            $kube->keterangan ?? '-'        // 🔥 Mapping keterangan
        ];
    }
}