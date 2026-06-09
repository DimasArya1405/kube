<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediksi_kube', function (Blueprint $table) {

            // PRIMARY KEY INT(9)
            $table->id('id_prediksi');

            // INT(9) sementara dibuat biasa dulu
            $table->integer('id_kube');
            $table->integer('id_pendamping');

            // DECIMAL(12,2)
            $table->decimal('omset_sesudah', 12, 2)->nullable();

            // WAKTU
            $table->tinyInteger('bulan')->length(2); // 1 - 12
            $table->year('tahun');

            // Q1
            $table->enum('q1_usaha_berjalan', ['ya', 'tidak']);
            $table->text('q1_keterangan')->nullable();

            // Q2
            $table->enum('q2_potensi_berkelanjutan', ['ya', 'tidak']);
            $table->text('q2_keterangan')->nullable();

            // Q3
            $table->enum('q3_kekompakan_kelompok', ['ya', 'tidak']);
            $table->text('q3_keterangan')->nullable();

            // Q4
            $table->enum('q4_menghasilkan_keuntungan', ['ya', 'tidak']);
            $table->text('q4_keterangan')->nullable();

            // Q5
            $table->enum('q5_perkembangan_usaha', ['ya', 'tidak']);
            $table->text('q5_keterangan')->nullable();

            // Q6
            $table->enum('q6_keaktifan_kube', ['ya', 'tidak']);
            $table->text('q6_keterangan')->nullable();

            // NILAI
            $table->integer('total_poin')->length(3)->nullable();

            // HASIL
            $table->enum('hasil_prediksi', ['berhasil', 'gagal']);

            // TIMESTAMP MANUAL
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediksi_kube');
    }
};