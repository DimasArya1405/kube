<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KubeSeeder extends Seeder
{
    public function run(): void
    {
        // 10 Data KUBE Dummy
        $kubes = [
            ['nama' => 'Maju Jaya', 'cluster' => 1, 'desa' => 1, 'status' => 'Aktif'],
            ['nama' => 'Ternak Sejahtera', 'cluster' => 2, 'desa' => 2, 'status' => 'Aktif'],
            ['nama' => 'Mina Berkah', 'cluster' => 3, 'desa' => 3, 'status' => 'Menunggu'],
            ['nama' => 'Tani Makmur', 'cluster' => 1, 'desa' => 1, 'status' => 'Aktif'],
            ['nama' => 'Srikandi Kreatif', 'cluster' => 4, 'desa' => 2, 'status' => 'Menunggu'],
            ['nama' => 'Berkah Bersama', 'cluster' => 2, 'desa' => 3, 'status' => 'Aktif'],
            ['nama' => 'Mandiri Sejahtera', 'cluster' => 3, 'desa' => 1, 'status' => 'Aktif'],
            ['nama' => 'Sumber Rejeki', 'cluster' => 1, 'desa' => 2, 'status' => 'Menunggu'],
            ['nama' => 'Harapan Maju', 'cluster' => 4, 'desa' => 3, 'status' => 'Aktif'],
            ['nama' => 'Karya Bersama', 'cluster' => 2, 'desa' => 1, 'status' => 'Aktif'],
        ];

        foreach ($kubes as $index => $k) {
            DB::table('kube')->insert([
                'nama_kube'         => $k['nama'],
                'id_cluster'        => $k['cluster'],
                'id_desa_kelurahan' => $k['desa'],
                'id_user'           => 1, // Pastikan ada User dengan ID 1 di tabel users (Ketua KUBE)
                // Tanggal terbentuk dibuat acak mundur dari hari ini supaya datanya bervariasi
                'tanggal_terbentuk' => now()->subDays(rand(1, 300))->format('Y-m-d'),
                'status'            => $k['status'],
                'keterangan'        => 'Kelompok binaan baru periode 2026 - KUBE ' . $k['nama'],
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}