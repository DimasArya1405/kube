<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('laporan_keuangan', function (Blueprint $table) {
        $table->id('id_laporan'); 
        $table->unsignedBigInteger('id_persetujuan')->nullable();
        $table->unsignedBigInteger('id_pendamping')->nullable();
        $table->integer('id_cluster');
        $table->date('tanggal_laporan');
        $table->integer('periode_bulan');
        $table->year('periode_tahun');
        $table->decimal('omset_pendapatan', 15, 2);
        $table->decimal('total_pengeluaran', 15, 2);
        $table->decimal('laba_bersih', 15, 2);
        $table->decimal('total_omset', 15, 2);
        $table->text('keterangan')->nullable();
        $table->string('lampiran_keuangan', 255)->nullable();
        $table->enum('status_validasi', ['Draft', 'Disetujui'])->default('Draft');
        $table->enum('progres_keuangan', ['Menurun', 'Tetap', 'Meningkat']);
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('laporan_keuangan');
    }
};