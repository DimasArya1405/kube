<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kube;

class ClusterUsaha extends Model
{
    protected $table = 'cluster_usaha';

    protected $primaryKey = 'id_cluster';

    protected $guarded = [];

    public function kube()
    {
        return $this->hasMany(Kube::class, 'id_cluster', 'id_cluster');
    }
}