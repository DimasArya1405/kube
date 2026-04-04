<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pendamping;

class PembagianPendamping extends Model
{
    // Nama tabel sesuai di gambar
    protected $table = 'pembagian_pendamping';

    // Primary key-nya
    protected $primaryKey = 'id_pembagian';

    protected $guarded = [];

    // Relasi ke tabel KUBE
    public function kube()
    {
        return $this->belongsTo(Kube::class, 'id_kube', 'id_kube');
    }

    public function pendamping()
    {
        return $this->belongsTo(Pendamping::class, 'id_pendamping', 'id_pendamping');
    }
}