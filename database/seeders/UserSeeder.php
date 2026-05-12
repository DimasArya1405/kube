<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'role' => 'admin',
                'nama' => 'Administrator Sistem',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin'),
            ],
            [
                'role' => 'ketua_kube',
                'nama' => 'Ketua KUBE User',
                'email' => 'ketua@gmail.com',
                'password' => Hash::make('ketua'),
            ],
            [
                'role' => 'pendamping',
                'nama' => 'Pendamping Lapangan',
                'email' => 'pendamping@gmail.com',
                'password' => Hash::make('pendamping'),
            ],
            [
                'role' => 'koordinator',
                'nama' => 'Koordinator Wilayah',
                'email' => 'koordinator@gmail.com',
                'password' => Hash::make('koordinator'),
            ],
            [
                'role' => 'kepala_dinas',
                'nama' => 'Kepala Dinas',
                'email' => 'kadis@gmail.com',
                'password' => Hash::make('kadis'),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'role' => $user['role'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'password' => $user['password'],
                'status' => 'aktif',
                'no_hp' => '08123456789',
                'alamat' => 'Alamat Default Seeder',
                'nik' => '1234567890123456',
                'id_kecamatan' => 1, 
                'id_desa_kelurahan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}