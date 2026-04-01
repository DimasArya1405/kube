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
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            // Primary Key
            $table->integer('id_laporan')->autoIncrement();

            // Foreign Keys (Menggunakan integer sesuai permintaan panjang 9)
            $table->integer('id_persetujuan');
            $table->integer('id_pendamping');
            $table->integer('id_cluster');

            // Detail Waktu
            $table->date('tanggal_laporan');
            $table->integer('periode_bulan'); // Panjang data bisa diatur di level validasi aplikasi
            $table->year('periode_tahun');

            // Data Keuangan (Decimal 15,2)
            $table->decimal('omset_pendapatan', 15, 2);
            $table->decimal('total_pengeluaran', 15, 2);
            $table->decimal('laba_bersih', 15, 2);
            $table->decimal('total_omset', 15, 2);

            // Tambahan & Lampiran
            $table->text('keterangan')->nullable();
            $table->string('lampiran_keuangan', 255)->nullable();

            // Status & Progress (Enum)
            $table->enum('status_validasi', ['Draft', 'Disetujui'])->default('Draft');
            $table->enum('progres_keuangan', ['Menurun', 'Tetap', 'Meningkat']);

            // Timestamps (created_at & updated_at)
            $table->timestamps();

            // Definisi Foreign Key Constraints (Opsional, pastikan tabel referensi sudah ada)
            // $table->foreign('id_persetujuan')->references('id')->on('persetujuan');
            // $table->foreign('id_pendamping')->references('id')->on('pendamping');
            // $table->foreign('id_cluster')->references('id')->on('cluster');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan');
    }
};