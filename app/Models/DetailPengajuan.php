<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuan extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'pengajuan_id',
        'id_jenis_bantuan',
        'nama_item',
        'jumlah'
    ];

    // 🔹 Relasi ke pengajuan utama
    public function pengajuan()
    {
        return $this->belongsTo(
            PengajuanKube::class,
            'pengajuan_id',
            'id_pengajuan_kube'
        );
    }

    // 🔹 Relasi ke jenis bantuan
    public function jenisBantuan()
    {
        return $this->belongsTo(
            JenisBantuan::class,
            'id_jenis_bantuan',
            'id_jenis_bantuan'
        );
    }
}