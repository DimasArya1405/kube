<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kube;

class AnggotaKube extends Model
{
    protected $table = 'anggota_kube';

    protected $primaryKey = 'id_anggota';

    protected $fillable = [
        'id_kube',
        'nama_anggota',
        'nik',
        'alamat',
        'no_hp',
        'jabatan',
    ];

    public function kube()
    {
        return $this->belongsTo(Kube::class, 'id_kube', 'id_kube');
    }
}