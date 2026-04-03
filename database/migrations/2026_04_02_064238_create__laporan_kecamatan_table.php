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
        Schema::create('laporan_kecamatan', function (Blueprint $table) {
            $table->increments('id_laporan_kecamatan');
            $table->integer('id_kecamatan');
            $table->integer('id_cluster');
            $table->year('periode_tahun');
            $table->integer('total_kube')->nullable();
            $table->integer('total_kube_aktif')->nullable();
            $table->integer('total_kube_tidak_aktif')->nullable();
            $table->decimal('total_omset', 15, 2)->nullable();
            $table->decimal('total_laba_bersih', 15, 2)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kecamatan');
    }
};