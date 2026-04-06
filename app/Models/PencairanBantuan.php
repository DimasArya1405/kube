<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PencairanBantuan extends Model
{
    protected $table = 'pencairan_bantuan';
    protected $primaryKey = 'id_pencairan';

    protected $fillable = [
        'id_pencairan',
        'id_pengajuan',
        'id_jenis_bantuan',
        'tahap',
        'nilai_bantuan',
        'tanggal_pengajuan',
        'tanggal_cair',
        'status_pencairan'
    ];

    /**
     * Relasi ke Tabel Pengajuan
     */
    // public function pengajuan(): BelongsTo
    // {
    //     return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id_pengajuan');
    // }

    /**
     * Relasi ke Tabel Jenis Bantuan
     */
    // public function jenisBantuan(): BelongsTo
    // {
    //     return $this->belongsTo(JenisBantuan::class, 'id_jenis_bantuan', 'id_jenis_bantuan');
    // }
}