<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BimbinganKube extends Model
{
    protected $table = 'bimbingan_kube_oleh_pendamping';
    protected $primaryKey = 'id_bimbingan';

    public $incrementing = true;
    protected $keyType = 'int';

    // 🔥 TAMBAH INI (biar gak error timestamp kalau tabel gak punya)
    public $timestamps = false;

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

    // 🔥 RELASI KE KUBE
    public function kube()
    {
        return $this->belongsTo(Kube::class, 'id_kube', 'id_kube');
    }

    // 🔥 RELASI KE PENDAMPING
    public function pendamping()
    {
        return $this->belongsTo(Pendamping::class, 'id_pendamping', 'id_pendamping');
    }
}