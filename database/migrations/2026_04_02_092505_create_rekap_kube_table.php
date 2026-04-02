<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekap_kube', function (Blueprint $table) {
            $table->increments('id_rekap'); // INT(9) PK Auto Increment
            $table->unsignedTinyInteger('periode_bulan'); // Bulan rekap (1-12)
            $table->unsignedSmallInteger('periode_tahun'); // Tahun periode rekap
            $table->string('kecamatan', 100); // Nama kecamatan
            $table->unsignedSmallInteger('total_kube'); // Jumlah seluruh KUBE
            $table->unsignedSmallInteger('total_aktif'); // Jumlah KUBE aktif
            $table->unsignedSmallInteger('total_tidak_aktif'); // Jumlah KUBE tidak aktif
            $table->year('tahun_anggaran'); // Tahun anggaran laporan
            $table->timestamp('created_at')->useCurrent(); // Tanggal data dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_kube');
    }
};