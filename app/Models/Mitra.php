<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'mitra';

    // Mendefinisikan primary key kustom
    protected $primaryKey = 'id_mitra';

    // Kolom yang boleh diisi secara massal
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

    /**
     * Relasi ke Pelatihan
     * Satu mitra bisa terlibat di banyak pelatihan
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'id_mitra', 'id_mitra');
    }
}