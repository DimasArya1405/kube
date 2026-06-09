<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Barryvdh\DomPDF\Facade\Pdf;

class PembagianKoordinator extends Model
{
    protected $table = 'pembagian_koordinator';

    protected $primaryKey = 'id_pembagian_koor';

    public $timestamps = true;

    protected $fillable = [
        'id_koor',
        'id_pembagian',
        'status',
        'tgl_mulai',
        'tgl_selesai'
    ];

    // 🔥 WAJIB BIAR TANGGAL KEBACA BENAR
    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
    ];

    public function koordinator()
    {
        return $this->belongsTo(Koordinator::class, 'id_koor', 'id_koor');
    }

    public function pembagianPendamping()
    {
        return $this->belongsTo(PembagianPendamping::class, 'id_pembagian', 'id_pembagian');
    }

    public function exportPDF()
    {
        $data = PembagianKoordinator::with([
            'koordinator',
            'pembagianPendamping.pendamping',
            'pembagianPendamping.kube'
        ])->get()->groupBy('id_koor');

        return Pdf::loadView('admin.penugasan.pembagian_koordinator_pdf', [
            'data' => $data
        ])->download('laporan_pembagian_koordinator.pdf');
    }
}