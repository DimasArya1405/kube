<?php

namespace App\Exports;

use App\Models\AnggotaKube;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AnggotaKubeExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // Tarik data anggota sekalian bawa relasi ke tabel KUBE biar tau asal KUBE-nya
        return AnggotaKube::with('kube')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Anggota',
            'Asal KUBE',
            'Jabatan',
            'No. HP',
            'Alamat Lengkap'
        ];
    }

    public function map($anggota): array
    {
        static $no = 1;
        return [
            $no++,
            // Tanda petik di depan NIK & No HP biar Excel nggak ngubah angkanya jadi format aneh (scientific)
            "'" . $anggota->nik, 
            $anggota->nama_anggota,
            $anggota->kube->nama_kube ?? '-',
            $anggota->jabatan,
            "'" . $anggota->no_hp,
            $anggota->alamat
        ];
    }
}