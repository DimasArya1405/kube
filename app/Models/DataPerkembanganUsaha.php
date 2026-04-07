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
        'jumlah_tenaga_kerja',
        'perkembangan_usaha',
        'hasil_evaluasi',
        'rekomendasi',
        'status_hasil'
    ];

    /**
     * Relasi ke laporan keuangan
     */
    public function laporan()
    {
        return $this->belongsTo(Keuangan::class, 'id_laporan', 'id_laporan');
    }
}