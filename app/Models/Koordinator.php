<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kecamatan;

class Koordinator extends Model
{
    protected $table = 'koordinator';

    protected $primaryKey = 'id_koor';

    public $timestamps = true;

    protected $guarded = [];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }
}