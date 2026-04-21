<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitra';

    protected $primaryKey = 'id_mitra';

    protected $fillable = [
        'nama_mitra',
        'jenis_mitra',
        'no_telp',
        'email',
        'alamat',
        'nama_pic',
        'telp_pic',
        'mou',
        'tgl_mou',
        'masa_berlaku',
        'status',
    ];

    protected $casts = [
        'tgl_mou' => 'date:Y-m-d',
    ];

    public function bantuanKolaborasi(): HasMany
    {
        return $this->hasMany(KolaborasiBantuan::class, 'id_mitra');
    }

    /**
     * Relasi ke Pelatihan
     * Satu mitra bisa terlibat di banyak pelatihan
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'id_mitra', 'id_mitra');
    }
}
