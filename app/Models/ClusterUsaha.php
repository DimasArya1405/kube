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
}