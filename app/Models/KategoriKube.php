<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKube extends Model
{
    protected $table = 'kategori'; // nama tabel kamu

    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'status'
    ];
}