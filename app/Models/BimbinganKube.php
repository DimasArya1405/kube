<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kube;
use App\Models\Pendamping;

class BimbinganKube extends Model
{
    protected $table = 'bimbingan_kube_oleh_pendamping';
    protected $primaryKey = 'id_bimbingan';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_jadwal',
        'id_pendamping',
        'id_kube',
        'jenis_bimbingan',
        'materi_bimbingan',
        'tanggal_bimbingan',
        'hasil_bimbingan',
        'tindak_lanjut',
        'status_bimbingan',
        'lampiran'
    ];

    // Relasi ke KUBE
    public function kube()
    {
        return $this->belongsTo(Kube::class, 'id_kube', 'id_kube');
    }

    // ✅ FIX: Relasi ke Pendamping (BUKAN User)
    public function pendamping()
    {
        return $this->belongsTo(Pendamping::class, 'id_pendamping', 'id_pendamping');
    }
}