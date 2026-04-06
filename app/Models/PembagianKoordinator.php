<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembagianKoordinator extends Model
{
    protected $table = 'pembagian_koordinator';

    protected $primaryKey = 'id_pembagian_koor';

    public $timestamps = true;

    protected $fillable = [
        'id_koor',
        'id_pembagian',
        'status'
    ];

    // RELASI KE KOORDINATOR
    public function koordinator()
    {
        return $this->belongsTo(Koordinator::class, 'id_koor', 'id_koor');
    }

    // RELASI KE PEMBAGIAN PENDAMPING
    public function pembagianPendamping()
    {
        return $this->belongsTo(PembagianPendamping::class, 'id_pembagian', 'id_pembagian');
    }
}