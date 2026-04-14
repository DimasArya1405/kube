<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori')->insert([
            ['nama_kategori' => 'Pertanian', 'deskripsi' => 'Budidaya tanaman pangan', 'created_at' => now()],
            ['nama_kategori' => 'Peternakan', 'deskripsi' => 'Penggemukan dan pembibitan ternak', 'created_at' => now()],
            ['nama_kategori' => 'Perikanan', 'deskripsi' => 'Budidaya ikan air tawar dan laut', 'created_at' => now()],
            ['nama_kategori' => 'Kuliner', 'deskripsi' => 'Usaha makanan dan minuman olahan', 'created_at' => now()],
            ['nama_kategori' => 'Kerajinan', 'deskripsi' => 'Produksi barang seni dan kriya', 'created_at' => now()],
            ['nama_kategori' => 'Jasa Menjahit', 'deskripsi' => 'Konveksi dan perbaikan pakaian', 'created_at' => now()],
            ['nama_kategori' => 'Perbengkelan', 'deskripsi' => 'Servis kendaraan dan las besi', 'created_at' => now()],
            ['nama_kategori' => 'Perdagangan', 'deskripsi' => 'Warung sembako dan retail', 'created_at' => now()],
            ['nama_kategori' => 'Pengolahan Sampah', 'deskripsi' => 'Daur ulang limbah ekonomis', 'created_at' => now()],
            ['nama_kategori' => 'Budidaya Jamur', 'deskripsi' => 'Produksi jamur tiram dan kuping', 'created_at' => now()],
        ]);
    }
}