<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategori')->insert([
            ['nama_kategori' => 'Pertanian', 'deskripsi' => 'Budidaya tanaman pangan', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Peternakan', 'deskripsi' => 'Penggemukan dan pembibitan ternak', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Perikanan', 'deskripsi' => 'Budidaya ikan air tawar dan laut', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Kuliner', 'deskripsi' => 'Usaha makanan dan minuman olahan', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Kerajinan', 'deskripsi' => 'Produksi barang seni dan kriya', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Jasa Menjahit', 'deskripsi' => 'Konveksi dan perbaikan pakaian', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Perbengkelan', 'deskripsi' => 'Servis kendaraan dan las besi', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Perdagangan', 'deskripsi' => 'Warung sembako dan retail', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Pengolahan Sampah', 'deskripsi' => 'Daur ulang limbah ekonomis', 'status' => 'Aktif', 'created_at' => now()],
            ['nama_kategori' => 'Budidaya Jamur', 'deskripsi' => 'Produksi jamur tiram dan kuping', 'status' => 'Aktif', 'created_at' => now()],
        ]);
    }
}