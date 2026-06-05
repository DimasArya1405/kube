<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class KubeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil maksimal 10 user yang memiliki role 'ketua_kube'
        $ketuaKubeUsers = DB::table('users')
            ->where('role', 'ketua_kube')
            ->take(10)
            ->get();

        $dataKube = [];

        // Daftar status yang mungkin (karena di migration tipenya string, bukan enum)
        $pilihanStatus = ['Menunggu', 'Aktif', 'Disetujui', 'Ditolak'];

        foreach ($ketuaKubeUsers as $user) {
            $dataKube[] = [
                // Membuat nama KUBE yang unik, contoh: "KUBE Sejahtera", "KUBE Mandiri"
                'nama_kube'         => 'KUBE ' . $faker->company, 
                
                // Relasi diambil dari tabel users agar sinkron
                'id_user'           => $user->id_user,
                'id_desa_kelurahan' => $user->id_desa_kelurahan, // Disamakan dengan domisili ketua
                
                // Karena FK id_cluster di-komen, kita isi angka dummy antara 1 sampai 3
                'id_cluster'        => $faker->numberBetween(1, 3), 
                
                // Tanggal terbentuk diacak antara 2 tahun lalu sampai hari ini
                'tanggal_terbentuk' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                
                'status'            => $faker->randomElement($pilihanStatus),
                'keterangan'        => $faker->paragraph(2), // Keterangan acak
                
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        // Insert ke tabel kube
        DB::table('kube')->insert($dataKube);
    }
}