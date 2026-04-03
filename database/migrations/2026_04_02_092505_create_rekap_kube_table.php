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
            $table->increments('id_rekap_kube');
            $table->unsignedInteger('id_kecamatan');
            $table->year('tahun');
            $table->string('periode_bulan', 20);
            $table->integer('jumlah_kube');
            $table->integer('kube_aktif');
            $table->integer('kube_tidak_aktif');
            $table->timestamps();

            $table->foreign('id_kecamatan')
                  ->references('id_kecamatan')
                  ->on('kecamatan')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->unique(['id_kecamatan', 'tahun', 'periode_bulan'], 'rekap_kube_unique');
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