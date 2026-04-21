<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPrediksi extends Model
{
    use HasFactory;

    protected $table = 'hasil_prediksi';

    protected $primaryKey = 'id';

    protected $fillable = [
    'id_prediksi',
    'id_kube',
    'id_pendamping',
    'id_pertanyaan',
    'jawaban',
    'catatan',
    'bulan',
    'tahun',
];

    public function pertanyaan()
{
    return $this->belongsTo(Pertanyaan::class, 'id_pertanyaan', 'id');
}

// Relasi ke KUBE
public function kube()
{
    return $this->belongsTo(\App\Models\Kube::class, 'id_kube', 'id_kube');
} 

public $timestamps = true;
}