<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPerkembanganUsaha extends Model
{
    protected $table = 'data_perkembangan_usaha';
    protected $primaryKey = 'id_perkembangan';

    public $timestamps = true;

    protected $fillable = [
        'id_laporan',
        'periode_bulan',
        'periode_tahun',
        'omset_pendapatan',
        'total_pengeluaran',
        'laba_bersih',
        'total_omset',
        'jumlah_tenaga_kerja',
        'perkembangan_usaha',
        'hasil_evaluasi',
        'rekomendasi',
        'status_hasil',
    ];

    public function laporan()
    {
        return $this->belongsTo(Keuangan::class, 'id_laporan', 'id_laporan');
    }
}