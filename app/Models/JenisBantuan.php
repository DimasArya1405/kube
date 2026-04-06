<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBantuan extends Model
{
    protected $table = 'jenis_bantuan';
    protected $primaryKey = 'id_jenis_bantuan';

    protected $fillable = [
        'jenis_bantuan'
    ];

    public $timestamps = true;

    // 🔥 RELASI (opsional tapi bagus)
    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'id_jenis_bantuan');
    }
}