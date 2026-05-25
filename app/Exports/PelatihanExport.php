<?php

namespace App\Exports;

use App\Models\Pelatihan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PelatihanExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Mengambil data relasi
    */
    public function collection()
    {
        return Pelatihan::with(['kube', 'pendamping'])->get();
    }

    public function map($pelatihan): array
    {
        return [
            $pelatihan->nama_pelatihan,
            $pelatihan->kube->nama_kube ?? '-',
            $pelatihan->pendamping->nama_pendamping ?? '-',
            $pelatihan->mitra->nama_mitra ?? '-', // <-- Tambah Mitra
            $pelatihan->tanggal_mulai,            // <-- Tambah Tanggal Mulai
            $pelatihan->tanggal_selesai,          // <-- Tambah Tanggal Selesai
            $pelatihan->lokasi,
            $pelatihan->status,
            $pelatihan->deskripsi,                // <-- Tambah Deskripsi
        ];
    }

    public function headings(): array
    {
        return [
        'Nama Pelatihan',
        'KUBE',
        'Pendamping',
        'Mitra',          
        'Tgl Mulai',
        'Tgl Selesai',
        'Lokasi',
        'Status',
        'Deskripsi',     
    ];
    }
}