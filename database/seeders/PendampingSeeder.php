<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PendampingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil maksimal 10 user yang memiliki role 'pendamping'
        $pendampingUsers = DB::table('users')
            ->where('role', 'pendamping')
            ->take(10)
            ->get();

        $dataPendamping = [];

        foreach ($pendampingUsers as $user) {
            $dataPendamping[] = [
                // Mengambil data dari tabel users agar sinkron dan logis
                'nik'                 => substr($user->nik, 0, 16), // Pastikan max 16 karakter sesuai migration
                'nama_pendamping'     => $user->nama,
                'alamat'              => $user->alamat,
                'no_hp'               => $user->no_hp,
                'email'               => $user->email,
                'id_kecamatan'        => $user->id_kecamatan,
                'id_user'             => $user->id_user,
                
                // Data acak yang di-generate oleh Faker
                'jenis_kelamin'       => $faker->randomElement(['L', 'P']),
                'tempat_lahir'        => $faker->city,
                'tanggal_lahir'       => $faker->dateTimeBetween('-40 years', '-22 years')->format('Y-m-d'),
                'pendidikan_terakhir' => $faker->randomElement(['SMA', 'D3', 'S1', 'S2']),
                'tanggal_mulai'       => $faker->dateTimeBetween('-3 years', '-1 months')->format('Y-m-d'),
                'tanggal_selesai'     => null, // Dikosongkan karena diasumsikan masih menjabat
                
                // Status dan foto default
                'status'              => 'Aktif', // Perhatikan huruf kapital, harus sama dengan Enum di migration
                'foto'                => 'default-pendamping.png',
                
                'created_at'          => now(),
                'updated_at'          => now(),
            ];
        }

        // Insert ke tabel pendamping
        DB::table('pendamping')->insert($dataPendamping);
    }
}