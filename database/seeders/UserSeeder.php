<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manualUsers = [
            [
                'role'              => 'admin',
                'nama'              => 'Administrator Sistem',
                'email'             => 'admin@gmail.com',
                'password'          => Hash::make('admin'),
                'status'            => 'aktif',
                'no_hp'             => '081111111111',
                'alamat'            => 'Jl. Pusat Pemerintahan',
                'nik'               => '3201012345670001',
                'id_kecamatan'      => 1, 
                'id_desa_kelurahan' => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'role'              => 'ketua_kube',
                'nama'              => 'Ketua KUBE User',
                'email'             => 'ketua@gmail.com',
                'password'          => Hash::make('ketua'),
                'status'            => 'aktif',
                'no_hp'             => '082222222222',
                'alamat'            => 'Jl. Desa KUBE',
                'nik'               => '3201012345670002',
                'id_kecamatan'      => 2, 
                'id_desa_kelurahan' => 2,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'role'              => 'pendamping',
                'nama'              => 'Pendamping Lapangan',
                'email'             => 'pendamping@gmail.com',
                'password'          => Hash::make('pendamping'),
                'status'            => 'aktif',
                'no_hp'             => '083333333333',
                'alamat'            => 'Jl. Lapangan',
                'nik'               => '3201012345670003',
                'id_kecamatan'      => 3, 
                'id_desa_kelurahan' => 3,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'role'              => 'koordinator',
                'nama'              => 'Koordinator Wilayah',
                'email'             => 'koordinator@gmail.com',
                'password'          => Hash::make('koordinator'),
                'status'            => 'aktif',
                'no_hp'             => '084444444444',
                'alamat'            => 'Jl. Wilayah',
                'nik'               => '3201012345670004',
                'id_kecamatan'      => 4, 
                'id_desa_kelurahan' => 4,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'role'              => 'kepala_dinas',
                'nama'              => 'Kepala Dinas',
                'email'             => 'kadis@gmail.com',
                'password'          => Hash::make('kadis'),
                'status'            => 'aktif',
                'no_hp'             => '085555555555',
                'alamat'            => 'Jl. Kantor Dinas',
                'nik'               => '3201012345670005',
                'id_kecamatan'      => 5, 
                'id_desa_kelurahan' => 5,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ];

        // Insert data manual satu per satu agar lebih aman
        foreach ($manualUsers as $user) {
            DB::table('users')->insert($user);
        }
    }
}