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
            
            // Mencegah error Foreign Key:
            // Ambil id_desa_kelurahan secara acak berdasarkan id_kecamatan user terkait.
            // (Abaikan query ini jika tabel 'users' Anda sudah memiliki kolom 'id_desa_kelurahan').
            $desa = DB::table('desa_kelurahan')
                ->where('id_kecamatan', $user->id_kecamatan)
                ->inRandomOrder()
                ->first();

            // Gunakan id_desa_kelurahan dari $user jika ada, jika tidak gunakan hasil query $desa di atas.
            $idDesaKelurahan = $user->id_desa_kelurahan ?? ($desa->id_desa_kelurahan ?? null);

            $dataPendamping[] = [
                // Foreign Keys
                'id_user'             => $user->id_user,
                'id_kecamatan'        => $user->id_kecamatan,
                'id_desa_kelurahan'   => $idDesaKelurahan, // ← Tambahan field baru

                // Sinkronisasi data dari tabel users
                'nik'                 => substr($user->nik, 0, 16), // Pastikan max 16 karakter
                'nama_pendamping'     => $user->nama,
                'alamat'              => $user->alamat,
                'no_hp'               => $user->no_hp,
                'email'               => $user->email,
                
                // Data acak yang di-generate oleh Faker
                'jenis_kelamin'       => $faker->randomElement(['L', 'P']),
                'tempat_lahir'        => $faker->city,
                'tanggal_lahir'       => $faker->dateTimeBetween('-40 years', '-22 years')->format('Y-m-d'),
                'pendidikan_terakhir' => $faker->randomElement(['SMA', 'D3', 'S1', 'S2']),
                'tanggal_mulai'       => $faker->dateTimeBetween('-3 years', '-1 months')->format('Y-m-d'),
                'tanggal_selesai'     => null, // Dikosongkan karena diasumsikan masih menjabat
                
                // Status dan foto default
                'status'              => 'Aktif',
                'foto'                => 'default-pendamping.png',
                
                // Timestamps
                'created_at'          => now(),
                'updated_at'          => now(),
            ];
        }

        // Insert ke tabel pendamping
        DB::table('pendamping')->insert($dataPendamping);
    }
}