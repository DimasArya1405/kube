<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Koordinator extends Model
{
    protected $table = 'koordinator';
    protected $primaryKey = 'id_koor';

    protected $fillable = [
        'id_koor',
        'id_user',
        'nik',
        'nama_koordinator',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'email',
        'pendidikan_terakhir',
        'id_kecamatan',
        'id_desa_kelurahan',
        'wilayah',
        'status',
        'foto',
    ];

    /**
     * Relasi ke Tabel User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke Tabel Kecamatan
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Relasi ke Tabel Desa/Kelurahan
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(DesaKelurahan::class, 'id_desa_kelurahan', 'id_desa_kelurahan');
    }
}