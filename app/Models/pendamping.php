<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kecamatan;

class Pendamping extends Model
{
    protected $table = 'pendamping';

    protected $primaryKey = 'id_pendamping';

    public $timestamps = true;

    protected $guarded = [];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

        public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}