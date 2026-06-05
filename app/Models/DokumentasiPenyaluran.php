<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumentasiPenyaluran extends Model
{
    protected $table = 'dokumentasi_penyaluran';

    protected $fillable = [
        'id_bukti',
        'foto_path',
    ];

    // Relasi balik ke Bukti Penyaluran
    public function bukti()
    {
        return $this->belongsTo(BuktiPenyaluran::class, 'id_bukti', 'id_bukti');
    }
}