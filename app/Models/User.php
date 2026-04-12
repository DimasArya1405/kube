<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Primary key custom
    protected $primaryKey = 'id_user';

    // Field yang boleh diisi
    protected $fillable = [
        'nama',
        'nik',
        'email',
        'password',
        'no_hp',
        'alamat',
        'id_kecamatan',
        'id_desa_kelurahan',
        'role',
        'status'
    ];

    // Field yang disembunyikan
    protected $hidden = [
        'password',
    ];

    // Casting
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function desa()
    {
        return $this->belongsTo(DesaKelurahan::class, 'id_desa_kelurahan', 'id_desa_kelurahan');
    }
}