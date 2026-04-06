<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendamping extends Model
{
    protected $table = 'pendamping';
    protected $primaryKey = 'id_pendamping';

    protected $fillable = [
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
        'tahun_mulai',
        'status',
        'foto'
    ];

    public $timestamps = true;

    // 🔥 RELASI ke monitoring
    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'id_pendamping');
    }
}