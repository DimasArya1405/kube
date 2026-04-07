<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan';

    protected $primaryKey = 'id_pertanyaan';

    public $timestamps = false;

    protected $fillable = [
        'pertanyaan',
    ];

    // Relasi ke hasil prediksi
    public function hasilPrediksi()
    {
        return $this->hasMany(HasilPrediksi::class, 'id_pertanyaan', 'id_pertanyaan');
    }
}