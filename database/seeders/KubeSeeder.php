<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KubeSeeder extends Seeder
{
    public function run(): void
    {
        $kubes = [
            ['nama' => 'Maju Jaya', 'cluster' => 1, 'desa' => 1],
            ['nama' => 'Ternak Sejahtera', 'cluster' => 2, 'desa' => 2],
            ['nama' => 'Mina Berkah', 'cluster' => 3, 'desa' => 3],
            ['nama' => 'Dapur Wangi', 'cluster' => 4, 'desa' => 4],
            ['nama' => 'Bambu Sakti', 'cluster' => 5, 'desa' => 5],
            ['nama' => 'Jahit Rapi', 'cluster' => 6, 'desa' => 6],
            ['nama' => 'Bengkel Kuat', 'cluster' => 7, 'desa' => 7],
            ['nama' => 'Warung Kita', 'cluster' => 8, 'desa' => 8],
            ['nama' => 'Sampah Emas', 'cluster' => 9, 'desa' => 9],
            ['nama' => 'Jamur Makmur', 'cluster' => 10, 'desa' => 10],
        ];

        foreach ($kubes as $k) {
            DB::table('kube')->insert([
                'nama_kube' => $k['nama'],
                'id_cluster' => $k['cluster'],
                'id_desa_kelurahan' => $k['desa'],
                'tanggal_terbentuk' => now()->format('Y-m-d'),
                'status' => 'Aktif',
                'keterangan' => 'Kelompok binaan baru periode 2026',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}