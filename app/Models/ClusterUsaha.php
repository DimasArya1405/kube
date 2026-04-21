<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kube;

class ClusterUsaha extends Model
{
    protected $table = 'cluster_usaha';
    protected $primaryKey = 'id_cluster';
    public $timestamps = false;

    protected $fillable = [
        'nama_cluster',
        'deskripsi',
        'id_kategori',
        'status'
    ];

    public function kube()
    {
        return $this->hasMany(Kube::class, 'id_cluster', 'id_cluster');
    }

        public function kategori()
    {
    return $this->belongsTo(KategoriKube::class, 'id_kategori', 'id_kategori');
    }
}