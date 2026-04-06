<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PembagianPendamping;


class KunjunganPendamping extends Model
{
    protected $table = 'kunjungan_pendamping';
    protected $primaryKey = 'id_kunjungan';

    protected $fillable = [
    'id_pembagian',
    'tanggal_kunjungan',
    'waktu_kunjungan',
    'tujuan_kunjungan',
    'kunjungan_ke'
    ];

    public function pembagian()
    {
        return $this->belongsTo(PembagianPendamping::class, 'id_pembagian');
    }
}