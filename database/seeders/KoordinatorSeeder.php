<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $koordinators = [
            [
                'id_kecamatan'  => 1, // Pastikan kecamatan dengan ID 1 sudah ada
                'nama_koor'     => 'Budi Santoso',
                'nik'           => '3201011122330001',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Jl. Merdeka Blok A No. 10, Kecamatan Satu',
                'no_hp'         => '081234567890',
                'tgl_mulai'     => Carbon::now()->subYears(2)->format('Y-m-d'), // Mulai 2 tahun lalu
                'status'        => 'aktif',
                'foto'          => null,
            ],
            [
                'id_kecamatan'  => 2,
                'nama_koor'     => 'Siti Aminah',
                'nik'           => '3201024455660002',
                'jenis_kelamin' => 'P',
                'alamat'        => 'Jl. Sudirman No. 45, Kecamatan Dua',
                'no_hp'         => '085678901234',
                'tgl_mulai'     => Carbon::now()->subYear()->format('Y-m-d'), // Mulai 1 tahun lalu
                'status'        => 'aktif',
                'foto'          => null,
            ],
            [
                'id_kecamatan'  => 3,
                'nama_koor'     => 'Ahmad Fauzi',
                'nik'           => '3201037788990003',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Perumahan Asri Indah No. 7, Kecamatan Tiga',
                'no_hp'         => '089876543210',
                'tgl_mulai'     => Carbon::now()->subMonths(6)->format('Y-m-d'),
                'status'        => 'aktif',
                'foto'          => null,
            ],
            [
                'id_kecamatan'  => 1,
                'nama_koor'     => 'Ratna Sari',
                'nik'           => '3201019900110004',
                'jenis_kelamin' => 'P',
                'alamat'        => 'Jl. Melati Raya No. 12, Kecamatan Satu',
                'no_hp'         => '081122334455',
                'tgl_mulai'     => Carbon::now()->subYears(3)->format('Y-m-d'),
                'status'        => 'non-aktif',
                'foto'          => null,
            ],
            [
                'id_kecamatan'  => 2,
                'nama_koor'     => 'Hendra Wijaya',
                'nik'           => '3201022233440005',
                'jenis_kelamin' => 'L',
                'alamat'        => 'Jl. Pahlawan No. 88, Kecamatan Dua',
                'no_hp'         => '082233445566',
                'tgl_mulai'     => Carbon::now()->subMonths(2)->format('Y-m-d'),
                'status'        => 'aktif',
                'foto'          => null,
            ],
        ];

        foreach ($koordinators as $koor) {
            DB::table('koordinator')->insert([
                'id_kecamatan'  => $koor['id_kecamatan'],
                'nama_koor'     => $koor['nama_koor'],
                'nik'           => $koor['nik'],
                'jenis_kelamin' => $koor['jenis_kelamin'],
                'alamat'        => $koor['alamat'],
                'no_hp'         => $koor['no_hp'],
                'tgl_mulai'     => $koor['tgl_mulai'],
                'status'        => $koor['status'],
                'foto'          => $koor['foto'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}