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

            // Foreign Keys (id_kube DIHAPUS DARI SINI)
            $table->TEXT('id_pendamping')->nullable(); // Sebaiknya nullable kalau misal belum ada pendamping
            $table->integer('id_mitra')->unsigned()->nullable();      // Sebaiknya nullable juga

            // Data utama
            $table->string('nama_pelatihan', 150);
            $table->string('jenis_pelatihan', 255);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('lokasi', 150);
            $table->text('deskripsi')->nullable();

            // Enum status
            $table->enum('status', ['Terjadwal', 'Selesai', 'Dibatalkan']);

            // Timestamps
            $table->timestamps();

            // Foreign Key Constraints (Buka commentnya biar aman)
            // Asumsi: tabel pendamping PK-nya id_pendamping, tabel mitra PK-nya id_mitra
            // $table->foreign('id_pendamping')->references('id_pendamping')->on('pendamping')->onDelete('set null');
            // $table->foreign('id_mitra')->references('id_mitra')->on('mitra')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelatihan');
    }
};