<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KolaborasiBantuan extends Model
{
    use HasFactory;
    protected $table = 'bantuan_kolaborasi';
    protected $primaryKey = 'id_kolaborasi';
    protected $fillable = [
        'id_mitra',
        'id_kube',
        'jenis_bantuan',
        'nama_bantuan',
        'tgl_pelaksanaan',
        'bantuan',
        'foto_bukti',
        'status',
    ];
    public function mitra(): BelongsTo
    {
        return $this->belongsTo(Mitra::class, 'id_mitra', 'id_mitra');
    }
    public function kube(): BelongsTo
    {
        return $this->belongsTo(Kube::class, 'id_kube', 'id_kube');
    }
}
