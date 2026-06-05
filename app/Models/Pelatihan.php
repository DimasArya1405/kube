<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Hapus atau biarkan use App\Models\Kube; dll (bebas)

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';
    protected $primaryKey = 'id_pelatihan';

    protected $fillable = [
        'id_pendamping',
        // 'id_kube',  <--- INI HARUS DIHAPUS KARENA SUDAH GAK ADA DI TABEL PELATIHAN
        'id_mitra',
        'nama_pelatihan',
        'jenis_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'deskripsi',
        'status'
    ];

    public function kubes()
    {
        return $this->belongsToMany(Kube::class, 'kube_pelatihan', 'id_pelatihan', 'id_kube');
    }

    public function pendamping()
    {
        return $this->belongsTo(Pendamping::class, 'id_pendamping', 'id_pendamping');
    }
    
    public function mitra() 
    { 
        return $this->belongsTo(Mitra::class, 'id_mitra'); 
    }
}