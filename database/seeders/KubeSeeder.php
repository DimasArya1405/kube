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
            // ... dst
        ];

        foreach ($kubes as $k) {
            DB::table('kube')->insert([
                'nama_kube'         => $k['nama'],
                'id_cluster'        => $k['cluster'],
                'id_desa_kelurahan' => $k['desa'],
                'id_user'           => 1, // TAMBAHKAN INI: Karena di migrasi wajib ada id_user
                'tanggal_terbentuk' => now()->format('Y-m-d'),
                'status'            => 'Aktif',
                'keterangan'        => 'Kelompok binaan baru periode 2026',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}
