<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mitra extends Model
{
    use HasFactory;
    
    protected $table = 'mitra'; //nama tabel

    protected $primaryKey = 'id_mitra'; //mendefinisikan primary key

    protected $fillable = [ //kolom yang boleh diisi melalui form store/update
        'nama_mitra',
        'jenis_mitra',
        'no_telp',
        'email',
        'alamat',
        'nama_pic',
        'telp_pic',
        'mou',
        'tgl_mou',
        'masa_berlaku',
        
    ];
    
    // Mengubah string dari database menjadi objek Date secara otomatis,
    // sehingga kita bisa pakai fungsi tgl->format() atau operasi matematika tanggal.
    protected $casts = [
        'tgl_mou' => 'date:Y-m-d',
    ];

    //Relasi : satu mitra memilik banyak bantuan kolaborasi
    public function bantuanKolaborasi(): HasMany
    {
        // hasMany(NamaModelTujuan, Foreign_Key_di_tabel_tujuan, Local_Key_di_tabel_ini)
        return $this->hasMany(KolaborasiBantuan::class, 'id_mitra');
    }

    /**
     * Relasi ke Pelatihan
     * Satu mitra bisa terlibat di banyak pelatihan
     */
    public function pelatihans()
    {
        return $this->hasMany(Pelatihan::class, 'id_mitra', 'id_mitra');
    }

    //Jika hari ini melewati (Tanggal MOU + Masa Berlaku), maka otomatis 'Tidak Aktif'.
    public function getStatusAttribute()
    {
        // Hitung tanggal berakhir (Tanggal MOU + Masa Berlaku Tahun)
        $tanggalBerakhir = \Carbon\Carbon::parse($this->tgl_mou)->addYears($this->masa_berlaku);

        // Jika sekarang > tanggal berakhir, return 'Tidak Aktif'
        if (\Carbon\Carbon::now()->greaterThan($tanggalBerakhir)) {
            return 'Tidak Aktif';
        }

        return 'Aktif';
    }
    public function bantuan_kolaborasi()
    {
        return $this->hasMany(KolaborasiBantuan::class, 'id_mitra', 'id_mitra');
    }
}
