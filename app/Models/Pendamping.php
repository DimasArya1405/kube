<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendamping extends Model
{
    use HasFactory;

    protected $table = 'pendamping';

    protected $primaryKey = 'id_pendamping';

    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'nik',
        'nama_pendamping',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'email',
        'pendidikan_terakhir',
        'id_kecamatan',
        'id_desa',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'foto',
    ];

    /**
     * Relasi ke User — kecamatan & desa diambil dari sini
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke Pelatihan
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'id_pendamping', 'id_pendamping');
    }

    /**
     * Relasi ke Pembagian Koordinator
     */
    public function pembagianKoordinator()
    {
        return $this->hasOne(PembagianKoordinator::class, 'id_pendamping', 'id_pendamping');
    }
}