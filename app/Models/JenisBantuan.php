<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBantuan extends Model
{
    protected $table = 'jenis_bantuan';
    protected $primaryKey = 'id_jenis_bantuan';

    protected $fillable = [
        'id_jenis_bantuan',
        'jenis_bantuan',
        'created_at',
        'updated_at'
    ];

    // 🔥 RELASI (opsional tapi bagus)
    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'id_jenis_bantuan');
    }
}
