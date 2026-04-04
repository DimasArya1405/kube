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

            $table->foreignId('id_jenis_bantuan')
                  ->constrained('jenis_bantuan')
                  ->cascadeOnDelete();

            $table->foreignId('id_kube')
                  ->constrained('kube')
                  ->cascadeOnDelete();

            $table->foreignId('id_pendamping')
                  ->constrained('pendamping')
                  ->cascadeOnDelete();

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