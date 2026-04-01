<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id('id_kecamatan');
            $table->string('nama_kecamatan', 100);


            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        DB::table('kecamatan')->insert([
            ['id_kecamatan' => 1, 'nama_kecamatan' => 'Adipala'],
            ['id_kecamatan' => 2, 'nama_kecamatan' => 'Bantarsari'],
            ['id_kecamatan' => 3, 'nama_kecamatan' => 'Binangun'],
            ['id_kecamatan' => 4, 'nama_kecamatan' => 'Cilacap Selatan'],
            ['id_kecamatan' => 5, 'nama_kecamatan' => 'Cilacap Tengah'],
            ['id_kecamatan' => 6, 'nama_kecamatan' => 'Cilacap Utara'],
            ['id_kecamatan' => 7, 'nama_kecamatan' => 'Cimanggu'],
            ['id_kecamatan' => 8, 'nama_kecamatan' => 'Cipari'],
            ['id_kecamatan' => 9, 'nama_kecamatan' => 'Dayeuhluhur'],
            ['id_kecamatan' => 10, 'nama_kecamatan' => 'Gandrungmangu'],
            ['id_kecamatan' => 11, 'nama_kecamatan' => 'Jeruklegi'],
            ['id_kecamatan' => 12, 'nama_kecamatan' => 'Kampung Laut'],
            ['id_kecamatan' => 13, 'nama_kecamatan' => 'Karangpucung'],
            ['id_kecamatan' => 14, 'nama_kecamatan' => 'Kawunganten'],
            ['id_kecamatan' => 15, 'nama_kecamatan' => 'Kedungreja'],
            ['id_kecamatan' => 16, 'nama_kecamatan' => 'Kesugihan'],
            ['id_kecamatan' => 17, 'nama_kecamatan' => 'Kroya'],
            ['id_kecamatan' => 18, 'nama_kecamatan' => 'Majenang'],
            ['id_kecamatan' => 19, 'nama_kecamatan' => 'Maos'],
            ['id_kecamatan' => 20, 'nama_kecamatan' => 'Nusawungu'],
            ['id_kecamatan' => 21, 'nama_kecamatan' => 'Patimuan'],
            ['id_kecamatan' => 22, 'nama_kecamatan' => 'Sampang'],
            ['id_kecamatan' => 23, 'nama_kecamatan' => 'Sidareja'],
            ['id_kecamatan' => 24, 'nama_kecamatan' => 'Wanareja'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
