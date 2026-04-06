<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_persetujuan', 
        'id_cluster',
        'tanggal_laporan',
        'periode_bulan',
        'periode_tahun',
        'omset_pendapatan',
        'total_pengeluaran',
        'laba_bersih',
        'total_omset',
        'keterangan',
        'lampiran_keuangan',
        'status_validasi',
        'progres_keuangan'
    ];

    public function cluster()
    {
        return $this->belongsTo(ClusterUsaha::class, 'id_cluster', 'id_cluster');
    }
}