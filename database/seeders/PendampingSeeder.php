<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PendampingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pendampings = [
            [
                'nik'                 => '3201012345670002',
                'nama_pendamping'     => 'Rana', // Sesuai dengan data users id_user = 2
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Bandung',
                'tanggal_lahir'       => '1995-08-15',
                'alamat'              => 'Jl. Melati No. 2, Kota A',
                'no_hp'               => '082222222222',
                'email'               => 'rana123@gmail.com',
                'pendidikan_terakhir' => 'S1 Kesejahteraan Sosial',
                'id_kecamatan'        => 1, // Pastikan id_kecamatan 1 ada
                'id_user'             => 2, // Relasi ke tabel users
                'tanggal_mulai'       => Carbon::now()->subYears(2)->format('Y-m-d'),
                'tanggal_selesai'     => null,
                'status'              => 'Aktif',
                'foto'                => null,
            ],
            [
                'nik'                 => '3201021122334455',
                'nama_pendamping'     => 'Andi Pratama',
                'jenis_kelamin'       => 'L',
                'tempat_lahir'        => 'Jakarta',
                'tanggal_lahir'       => '1992-04-20',
                'alamat'              => 'Jl. Manggis Blok B No. 4, Kecamatan Dua',
                'no_hp'               => '081333444555',
                'email'               => 'andi.pratama@gmail.com',
                'pendidikan_terakhir' => 'S1 Sosiologi',
                'id_kecamatan'        => 2,
                'id_user'             => 3, // Pastikan id_user 3 ada di tabel users
                'tanggal_mulai'       => Carbon::now()->subYear()->format('Y-m-d'),
                'tanggal_selesai'     => null,
                'status'              => 'Aktif',
                'foto'                => null,
            ],
            [
                'nik'                 => '3201039988776655',
                'nama_pendamping'     => 'Sinta Dewi',
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Surabaya',
                'tanggal_lahir'       => '1994-11-10',
                'alamat'              => 'Perum Griya Asri No. 12, Kecamatan Tiga',
                'no_hp'               => '085777888999',
                'email'               => 'sintadewi@gmail.com',
                'pendidikan_terakhir' => 'S1 Psikologi',
                'id_kecamatan'        => 3,
                'id_user'             => 4, // Pastikan id_user 4 ada di tabel users
                'tanggal_mulai'       => Carbon::now()->subMonths(8)->format('Y-m-d'),
                'tanggal_selesai'     => null,
                'status'              => 'Aktif',
                'foto'                => null,
            ],
            [
                'nik'                 => '3201015566778899',
                'nama_pendamping'     => 'Rizky Fadilah',
                'jenis_kelamin'       => 'L',
                'tempat_lahir'        => 'Semarang',
                'tanggal_lahir'       => '1990-01-25',
                'alamat'              => 'Jl. Pahlawan No. 45, Kecamatan Satu',
                'no_hp'               => '081223344556',
                'email'               => 'rizky.f@gmail.com',
                'pendidikan_terakhir' => 'S1 Pembangunan Sosial',
                'id_kecamatan'        => 1,
                'id_user'             => 5, // Pastikan id_user 5 ada di tabel users
                'tanggal_mulai'       => Carbon::now()->subYears(3)->format('Y-m-d'),
                'tanggal_selesai'     => Carbon::now()->subMonths(2)->format('Y-m-d'), // Contoh yang sudah selesai
                'status'              => 'Tidak Aktif',
                'foto'                => null,
            ],
            [
                'nik'                 => '3201024433221100',
                'nama_pendamping'     => 'Maya Sari',
                'jenis_kelamin'       => 'P',
                'tempat_lahir'        => 'Yogyakarta',
                'tanggal_lahir'       => '1996-07-07',
                'alamat'              => 'Jl. Kenanga Raya No. 9, Kecamatan Dua',
                'no_hp'               => '089666555444',
                'email'               => 'mayasari@gmail.com',
                'pendidikan_terakhir' => 'D4 Pekerjaan Sosial',
                'id_kecamatan'        => 2,
                'id_user'             => 1, // Pastikan id_user 1 ada di tabel users
                'tanggal_mulai'       => Carbon::now()->subMonths(4)->format('Y-m-d'),
                'tanggal_selesai'     => null,
                'status'              => 'Aktif',
                'foto'                => null,
            ],
        ];

        foreach ($pendampings as $p) {
            DB::table('pendamping')->insert([
                'nik'                 => $p['nik'],
                'nama_pendamping'     => $p['nama_pendamping'],
                'jenis_kelamin'       => $p['jenis_kelamin'],
                'tempat_lahir'        => $p['tempat_lahir'],
                'tanggal_lahir'       => $p['tanggal_lahir'],
                'alamat'              => $p['alamat'],
                'no_hp'               => $p['no_hp'],
                'email'               => $p['email'],
                'pendidikan_terakhir' => $p['pendidikan_terakhir'],
                'id_kecamatan'        => $p['id_kecamatan'],
                'id_user'             => $p['id_user'],
                'tanggal_mulai'       => $p['tanggal_mulai'],
                'tanggal_selesai'     => $p['tanggal_selesai'],
                'status'              => $p['status'],
                'foto'                => $p['foto'],
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }
    }
}