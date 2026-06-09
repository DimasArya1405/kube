<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPenyaluran extends Model
{
    use HasFactory;

    protected $table = 'bukti_penyaluran';
    protected $primaryKey = 'id_bukti';

    protected $fillable = [
        'id_kolaborasi',
        'tgl_penyaluran',
        'catatan_pelaksanaan',
    ];

    // Relasi balik ke Bantuan Kolaborasi
    public function kolaborasi()
    {
        return $this->belongsTo(KolaborasiBantuan::class, 'id_kolaborasi', 'id_kolaborasi');
    }

    // Relasi ke banyak foto dokumentasi
    public function dokumentasi()
    {
        return $this->hasMany(DokumentasiPenyaluran::class, 'id_bukti', 'id_bukti');
    }
}