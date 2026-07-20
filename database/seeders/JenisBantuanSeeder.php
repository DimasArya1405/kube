<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisBantuanSeeder extends Seeder
{
        public function run(): void
    {
        DB::table('jenis_bantuan')->insert([
            ['jenis_bantuan' => 'Uang', 'created_at' => now()],
            ['jenis_bantuan' => 'Pakan', 'created_at' => now()],
            ['jenis_bantuan' => 'Alat','created_at' => now()],
        ]);
    }
}
