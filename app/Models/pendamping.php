<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Kecamatan;

class Pendamping extends Model
{
    use HasFactory;

    protected $table = 'pendamping';

    protected $primaryKey = 'id_pendamping';

    public $timestamps = true;

    protected $guarded = [];

    protected $fillable = [
        'nik',
        'nama_pendamping',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'email',
        'pendidikan_terakhir',
        'id_kecamatan',
        'tahun_mulai',
        'status',
        'foto',
    ];

    /**
     * Relasi ke Kecamatan
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    /**
     * Relasi ke Pelatihan
     * Satu pendamping bisa mendampingi banyak pelatihan
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'id_pendamping', 'id_pendamping');
    }

    public function pembagianKoordinator()
    {
        return $this->hasOne(PembagianKoordinator::class, 'id_pembagian', 'id_pembagian');
    }
        public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
