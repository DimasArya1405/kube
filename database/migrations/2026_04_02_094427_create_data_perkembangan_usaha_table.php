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
        Schema::create('data_perkembangan_usaha', function (Blueprint $table) {
            $table->increments('id_hasil_kunjungan'); // INT(9) PK Auto Increment

            $table->unsignedInteger('id_kunjungan'); // FK ke tabel pendamping
            $table->unsignedInteger('id_kube'); // FK ke tabel kube

            $table->decimal('omset_sebelum', 12, 2)->nullable(); // Omset sebelum pendampingan
            $table->decimal('omset_sesudah', 12, 2)->nullable(); // Omset setelah pendampingan

            $table->unsignedTinyInteger('jumlah_tenaga_kerja')->nullable(); // Jumlah tenaga kerja

            $table->enum('perkembangan_usaha', ['Meningkat', 'Tetap', 'Menurun'])->nullable();
            $table->enum('tingkat_kemandirian', ['Rendah', 'Sedang', 'Tinggi'])->nullable();

            $table->text('hasil_evaluasi')->nullable(); // Ringkasan evaluasi
            $table->text('rekomendasi')->nullable(); // Rekomendasi pendamping

            $table->enum('status_hasil', ['Tercapai', 'Belum Tercapai'])->nullable();

            $table->timestamp('created_at')->useCurrent(); // Waktu input
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate(); // Waktu update

            // // Foreign Key
            // $table->foreign('id_kunjungan')
            //       ->references('id')
            //       ->on('pendamping')
            //       ->onDelete('cascade');

            // $table->foreign('id_kube')
            //       ->references('id')
            //       ->on('kube')
            //       ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_perkembangan_usaha');
    }
};