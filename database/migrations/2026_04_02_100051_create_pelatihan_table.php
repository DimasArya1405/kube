<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelatihan', function (Blueprint $table) {
            // Primary Key
            $table->integer('id_pelatihan')->autoIncrement();

            // Foreign Keys (sementara integer dulu)
            $table->integer('id_pendamping')->unsigned();
            $table->integer('id_kube')->unsigned();
            $table->integer('id_mitra')->unsigned();

            // Data utama
            $table->string('nama_pelatihan', 150);
            $table->string('jenis_pelatihan', 255);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('lokasi', 150);
            $table->text('deskripsi')->nullable();

            // Enum status
            $table->enum('status', ['Terjadwal', 'Selesai', 'Dibatalkan']);

            // Timestamps
            $table->timestamps();

            // Foreign Key Constraints
            // $table->foreign('id_pendamping')->references('id_pendamping')->on('pendamping')->onDelete('cascade');
            // $table->foreign('id_kube')->references('id_kube')->on('kube')->onDelete('cascade');
            // $table->foreign('id_mitra')->references('id_mitra')->on('mitra')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan');
    }
};