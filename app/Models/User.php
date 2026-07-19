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

public function pendamping()
{
    // Mengubungkan user ke tabel pendamping berdasarkan id_pendamping yang ada di tabel users
    return $this->belongsTo(Pendamping::class, 'id_pendamping');
}

/**
 * Mengambil data KUBE yang aktif dibina oleh Pendamping ini
 */
public function kubeBinaan()
{
    // Melalui model Pendamping, kita ambil relasi ke KUBE (jika ada tabel pivot pembagian_pendamping)
    // Atau jika relasinya langsung ditaruh di User, sesuaikan di bawah ini:
    return $this->hasOneThrough(
        Kube::class,
        \App\Models\PembagianPendamping::class, // Model dari tabel pembagian_pendamping kamu
        'id_pendamping', // Foreign key di tabel pembagian_pendamping
        'id_kube',       // Foreign key di tabel kube
        'id_pendamping', // Local key di tabel users
        'id_kube'        // Local key di tabel pembagian_pendamping
    )->where('status', 'Aktif'); // Pastikan hanya mengambil yang statusnya Aktif
}
}