<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';
    protected $primaryKey = 'id_pelatihan';

    protected $fillable = [
        'id_pendamping', // Akan menyimpan JSON array: ["pendamping_1", "koor_2"]
        'id_mitra',
        'nama_pelatihan',
        'jenis_pelatihan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'deskripsi',
        'status'
    ];

    // Beritahu Laravel bahwa id_pendamping adalah array (JSON)
    protected $casts = [
        'id_pendamping' => 'array',
    ];

    protected $appends = ['daftar_pengajar'];

    public function kubes()
    {
        return $this->belongsToMany(Kube::class, 'kube_pelatihan', 'id_pelatihan', 'id_kube');
    }
    
    public function mitra() 
    { 
        return $this->belongsTo(Mitra::class, 'id_mitra'); 
    }

    // Ganti relasi default dengan Accessor untuk mem-parsing ID gabungan
// Ganti relasi default dengan Accessor untuk mem-parsing ID gabungan
    public function getDaftarPengajarAttribute()
    {
        $raw = $this->id_pendamping;
        $ids = [];

        // PENJAGAAN TIPE DATA (Mencegah Error)
        if (is_array($raw)) {
            // Format baru sudah benar berupa array
            $ids = $raw;
        } elseif (is_numeric($raw)) {
            // Menangani data lama yang masih berupa integer tunggal
            $ids = ['pendamping_' . $raw];
        } elseif (is_string($raw)) {
            // Jaga-jaga jika data tersimpan sebagai string biasa
            $decoded = json_decode($raw, true);
            $ids = is_array($decoded) ? $decoded : [$raw];
        }

        $hasil = [];

        foreach ($ids as $item) {
            if (str_starts_with((string)$item, 'pendamping_')) {
                $id = str_replace('pendamping_', '', $item);
                $pendamping = Pendamping::find($id);
                if ($pendamping) {
                    $hasil[] = '[Pendamping] ' . $pendamping->nama_pendamping;
                }
            } elseif (str_starts_with((string)$item, 'koor_')) {
                $id = str_replace('koor_', '', $item);
                $koor = Koordinator::with('user')->find($id);
                if ($koor && $koor->user) {
                    $hasil[] = '[Koordinator] ' . $koor->user->name; 
                }
            }
        }

        return $hasil;
    }
}