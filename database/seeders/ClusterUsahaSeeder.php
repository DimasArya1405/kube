<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClusterUsahaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cluster_usaha')->insert([
            ['nama_cluster' => 'Padi Organik', 'id_kategori' => 1, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Sapi Potong', 'id_kategori' => 2, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Lele Sangkuriang', 'id_kategori' => 3, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Kue Tradisional', 'id_kategori' => 4, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Anyaman Bambu', 'id_kategori' => 5, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Konveksi Seragam', 'id_kategori' => 6, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Las Konstruksi', 'id_kategori' => 7, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Sembako Mandiri', 'id_kategori' => 8, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Kreasi Plastik', 'id_kategori' => 9, 'status' => 'Aktif', 'created_at' => now()],
            ['nama_cluster' => 'Jamur Krispi', 'id_kategori' => 10, 'status' => 'Aktif', 'created_at' => now()],
        ]);
    }
}