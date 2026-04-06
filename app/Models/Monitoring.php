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
}