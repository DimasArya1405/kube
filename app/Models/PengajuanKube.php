<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanKube extends Model
{
    protected $table = 'pengajuan_kube';
    protected $primaryKey = 'id_pengajuan_kube';

    protected $fillable = [
        'id_pengajuan_kube',
        'id_kube',
        'id_user',
        'disetujui_oleh',
        'id_jenis_bantuan',
        'jumlah_bantuan',
        'tujuan_pengajuan',
        'tanggal_pengajuan',
        'tanggal_disetujui',
        'keterangan',
        'status_pengajuan',
        'status_penerima'
    ];

    public function kube()
    {
        return $this->belongsTo(Kube::class, 'id_kube', 'id_kube');
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh', 'id_user');
    }

    public function jenisBantuan()
    {
        return $this->belongsTo(JenisBantuan::class, 'id_jenis_bantuan', 'id_jenis_bantuan');
    }
    public function pencairanBantuan()
    {
        return $this->hasMany(PencairanBantuan::class, 'id_pengajuan', 'id_pengajuan_kube');
    }
}
