<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Koordinator extends Model
{
    protected $table = 'koordinator';
    protected $primaryKey = 'id_koor';
    public $timestamps = true;
    protected $guarded = [];

    public function user()
    {
        // Sesuaikan foreign key dan owner key-nya
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
