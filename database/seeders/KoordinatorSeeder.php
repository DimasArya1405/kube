<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KoordinatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil maksimal 10 user yang memiliki role 'koordinator'
        // agar id_user yang dimasukkan valid dan tidak melanggar Foreign Key
        $koordinatorUsers = DB::table('users')
            ->where('role', 'koordinator')
            ->take(10)
            ->get();

        $dataKoordinator = [];

        foreach ($koordinatorUsers as $user) {
            $dataKoordinator[] = [
                'id_user'       => $user->id_user,
                // Foto diset default, atau Anda bisa gunakan $faker->imageUrl() jika ingin gambar acak
                'foto'          => 'default.png', 
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                // Umur diacak antara 25 sampai 45 tahun yang lalu
                'tanggal_lahir' => $faker->dateTimeBetween('-45 years', '-25 years')->format('Y-m-d'),
                'status'        => $faker->randomElement(['aktif', 'non-aktif']),
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        // Insert ke tabel koordinator
        DB::table('koordinator')->insert($dataKoordinator);
    }
}