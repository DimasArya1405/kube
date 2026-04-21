<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_perkembangan_usaha', function (Blueprint $table) {
            $table->increments('id_perkembangan');

            $table->unsignedInteger('id_laporan');

            $table->unsignedTinyInteger('periode_bulan'); // INT 2
            $table->unsignedSmallInteger('periode_tahun'); // INT 4

            $table->decimal('omset_pendapatan', 12, 2)->nullable();

            $table->unsignedSmallInteger('jumlah_tenaga_kerja')->nullable();

            $table->enum('perkembangan_usaha', ['Meningkat', 'Tetap', 'Menurun'])->nullable();
            $table->enum('tingkat_kemandirian', ['Rendah', 'Sedang', 'Tinggi'])->nullable();

            $table->text('hasil_evaluasi')->nullable();
            $table->text('rekomendasi')->nullable();

            $table->enum('status_hasil', ['Tercapai', 'Belum Tercapai'])->nullable();

            $table->timestamps();

            // Foreign Key
           // $table->foreign('id_laporan')
                  //->references('id')
                  //->on('laporan_keuangan') // sesuaikan dengan nama tabel kamu
                  //->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_perkembangan_usaha');
    }
};