<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\DesaKelurahan;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';

    protected $primaryKey = 'id_kecamatan';

    public $timestamps = true;

    protected $guarded = [];

    public function desa()
    {
        return $this->hasMany(DesaKelurahan::class, 'id_kecamatan', 'id_kecamatan');
    }
}