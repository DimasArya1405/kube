<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_prediksi', function (Blueprint $table) {
            $table->id(); // id_hasil
            $table->unsignedBigInteger('id_kube');
            $table->unsignedBigInteger('id_pendamping');
            $table->unsignedBigInteger('id_pertanyaan');
            $table->boolean('jawaban'); // 1=true/ya, 0=false/tidak
            $table->string('catatan', 255)->nullable();
            $table->integer('bulan'); // 1-12
            $table->integer('tahun'); // tahun prediksi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_prediksi');
    }
};