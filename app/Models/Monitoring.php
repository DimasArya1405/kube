<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $table = 'monitoringbantuan';
    protected $primaryKey = 'id_monitoring';

    protected $fillable = [
        'id_jenis_bantuan',
        'id_kube',
        'id_pendamping',
        'id_pencairan',
        'tanggal_monitoring',
        'kesesuaian',
        'catatan',
        'foto_monitoring'
    ];

    public $timestamps = true;

    // 🔥 RELASI
    public function jenisBantuan()
    {
        return $this->belongsTo(JenisBantuan::class, 'id_jenis_bantuan');
    }

    public function kube()
    {
        return $this->belongsTo(Kube::class, 'id_kube');
    }

    public function pendamping()
    {
        return $this->belongsTo(Pendamping::class, 'id_pendamping');
    }
    // Di dalam model Monitoring.php
public function pencairan()
{
    return $this->belongsTo(\App\Models\PencairanBantuan::class, 'id_pencairan', 'id_pencairan');
}

// Tambahkan ini agar bisa mengambil data dari tabel pengajuan
public function pengajuan()
{
    return $this->hasOneThrough(
        \App\Models\PengajuanKube::class,
        \App\Models\PencairanBantuan::class,
        'id_pencairan', // Foreign key di tabel pencairan_bantuan
        'id_pengajuan_kube', // Local key di tabel pengajuan_kube
        'id_pencairan', // Local key di tabel monitoringbantuan
        'id_pengajuan' // Foreign key di tabel pencairan_bantuan
    );
}
}