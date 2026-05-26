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
        // Tabel Utama untuk Bukti Penyaluran
        Schema::create('bukti_penyaluran', function (Blueprint $table) {
            $table->id('id_bukti');
            // Menghubungkan ke tabel bantuan_kolaborasi
            $table->unsignedBigInteger('id_kolaborasi');
            $table->date('tgl_penyaluran');
            $table->text('catatan_pelaksanaan');
            $table->timestamps();

            // Foreign Key: Jika data kolaborasi dihapus, bukti penyaluran ikut terhapus
            $table->foreign('id_kolaborasi')
                  ->references('id_kolaborasi')
                  ->on('bantuan_kolaborasi')
                  ->onDelete('cascade');
        });

        // Tabel Khusus untuk Dokumentasi Foto (Multiple Photos)
        Schema::create('dokumentasi_penyaluran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_bukti');
            $table->string('foto_path'); // Menyimpan nama file gambar
            $table->timestamps();

            $table->foreign('id_bukti')
                  ->references('id_bukti')
                  ->on('bukti_penyaluran')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_penyaluran');
        Schema::dropIfExists('bukti_penyaluran');
    }
};