<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DesaKelurahan;
use App\Models\ClusterUsaha;
use App\Models\AnggotaKube;

class Kube extends Model
{
    use HasFactory;

    protected $table = 'kube';

    protected $primaryKey = 'id_kube';

    protected $fillable = [
        'nama_kube',
        'id_desa_kelurahan',
        'id_cluster',
        'tanggal_terbentuk',
        'status',
        'keterangan',
    ];

    public function desa()
    {
        return $this->belongsTo(DesaKelurahan::class, 'id_desa_kelurahan', 'id_desa_kelurahan');
    }

    public function clusterUsaha()
    {
        return $this->belongsTo(ClusterUsaha::class, 'id_cluster', 'id_cluster');
    }

    public function anggota()
    {
        return $this->hasMany(AnggotaKube::class, 'id_kube', 'id_kube');
    }
}
