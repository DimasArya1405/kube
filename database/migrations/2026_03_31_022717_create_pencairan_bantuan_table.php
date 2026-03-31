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
        Schema::create('pencairan_bantuan', function (Blueprint $table) {
            $table->id('id_pencairan');

            // Foreign key (sementara jadi integer biasa dulu)
            $table->integer('id_pengajuan');
            $table->integer('id_jenis_bantuan');

            // Enum tahap pencairan (contoh bisa disesuaikan)
            $table->enum('tahap', ['1', '2', '3']);

            // Nilai bantuan
            $table->bigInteger('nilai_bantuan');

            // Tanggal
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_cair')->nullable();

            // Status pencairan
            $table->enum('status_pencairan', ['menunggu', 'cair', 'ditolak'])->default('menunggu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pencairan_bantuan');
    }
};