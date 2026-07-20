<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monitoringbantuan', function (Blueprint $table) {
            $table->id('id_monitoring');

            $table->unsignedBigInteger('id_jenis_bantuan');
            $table->unsignedBigInteger('id_kube');
            $table->unsignedBigInteger('id_pendamping');
            $table->unsignedBigInteger('id_pencairan');

            $table->date('tanggal_monitoring');

            $table->enum('kesesuaian', ['sesuai', 'tidak sesuai']);

            $table->text('catatan')->nullable();

            $table->string('foto_monitoring', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoringbantuan');
    }
};