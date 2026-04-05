<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Keuangan;

class DataPerkembanganUsaha extends Model
{
    protected $table = 'data_perkembangan_usaha';
    protected $primaryKey = 'id_perkembangan';

    protected $fillable = [
        'id_laporan',
        'jumlah_tenaga_kerja',
        'perkembangan_usaha',
        'tingkat_kemandirian',
        'hasil_evaluasi',
        'rekomendasi',
        'status_hasil'
    ];

    // RELASI KE LAPORAN
    public function laporan()
    {
        return $this->belongsTo(Keuangan::class, 'id_laporan', 'id_laporan');
    }
}