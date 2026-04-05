<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Kube;
use App\Models\Pendamping;
use App\Models\Mitra;


class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';
    protected $primaryKey = 'id_pelatihan';

    protected $fillable = [
        'id_pendamping',
        'id_kube',
        'id_mitra',
        'nama_pelatihan',
        'jenis_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'deskripsi',
        'status'
    ];

    public function kube()
{
    return $this->belongsTo(Kube::class, 'id_kube', 'id_kube');
}

public function pendamping()
{
    return $this->belongsTo(Pendamping::class, 'id_pendamping', 'id_pendamping');
}
    // Relasi (Opsional - sesuaikan dengan nama model Anda)
    public function mitra() { return $this->belongsTo(Mitra::class, 'id_mitra'); }
}