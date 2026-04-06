<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesaKelurahan extends Model
{
    protected $table = 'desa_kelurahan';

    protected $primaryKey = 'id_desa_kelurahan';

    public $timestamps = true;

    protected $guarded = [];

    public function kecamatan()
    {
    return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }
}
