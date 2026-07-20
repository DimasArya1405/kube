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
        Schema::create('bantuan_kolaborasi', function (Blueprint $table) {
            $table->id('id_kolaborasi');

            // Foreign Key ke tabel mitra (id_mitra)
            $table->unsignedBigInteger('id_mitra');
            $table->foreign('id_mitra')->references('id_mitra')->on('mitra')->onDelete('cascade');
            
            $table->string('id_kube');
            $table->foreign('id_kube')->references('id_kube')->on('kube')->onDelete('cascade');

            $table->string('jenis_bantuan', 100);
            $table->string('nama_bantuan', 255);
            $table->date('tgl_pelaksanaan');
            $table->text('bantuan'); // Sesuai tabel: bantuan (Text)
            $table->text('deskripsi');
            $table->string('foto_bukti', 255);

            // Status: Enum (Terencana, Berjalan, Selesai)
            $table->enum('status', ['Terencana', 'Berjalan', 'Selesai'])->default('Terencana');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bantuan_kolaborasi');
    }
};
