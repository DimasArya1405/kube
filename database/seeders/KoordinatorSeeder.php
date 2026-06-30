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

        // Ambil daftar id_kecamatan yang valid untuk foreign key
        $kecamatanIds = DB::table('kecamatan')->pluck('id_kecamatan')->toArray();

        $dataKoordinator = [];

        foreach ($koordinatorUsers as $user) {
            // Pilih kecamatan: pakai milik user jika ada, kalau tidak random dari tabel kecamatan
            $idKecamatan = $user->id_kecamatan ?? $faker->randomElement($kecamatanIds);

            // Ambil desa yang sesuai dengan kecamatan terpilih, agar foreign key valid
            $idDesa = DB::table('desa_kelurahan')
                ->where('id_kecamatan', $idKecamatan)
                ->inRandomOrder()
                ->value('id_desa_kelurahan');

            $dataKoordinator[] = [
                'id_user'             => $user->id_user,
                'nik'                 => $user->nik ?? $faker->unique()->numerify('################'),
                'nama_koordinator'    => $user->nama ?? $faker->name(),
                'jenis_kelamin'       => $faker->randomElement(['L', 'P']),
                'tempat_lahir'        => $faker->city(),
                'tanggal_lahir'       => $faker->dateTimeBetween('-45 years', '-25 years')->format('Y-m-d'),
                'alamat'              => $user->alamat ?? $faker->address(),
                'no_hp'               => $user->no_hp ?? $faker->numerify('08##########'),
                'email'               => $user->email ?? $faker->unique()->safeEmail(),
                'pendidikan_terakhir' => $faker->randomElement(['SD', 'SMP', 'SMA/SMK', 'D3', 'S1', 'S2']),
                'id_kecamatan'        => $idKecamatan,
                'id_desa_kelurahan'   => $idDesa,
                'wilayah'             => $faker->citySuffix(),
                'status'              => $faker->randomElement(['Aktif', 'Tidak Aktif']),
                'foto'                => 'default.png',
                'created_at'          => now(),
                'updated_at'          => now(),
            ];
        }

        // Insert ke tabel koordinator
        DB::table('koordinator')->insert($dataKoordinator);
    }
}