<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            // Primary Key
            $table->integer('id_peserta')->autoIncrement();

            // Foreign Keys
            $table->integer('id_pelatihan')->unsigned();
            $table->integer('id_anggota')->unsigned();

            // Timestamps
            $table->timestamps();

            // Constraints
            // $table->foreign('id_pelatihan')
            //       ->references('id_pelatihan')
            //       ->on('pelatihan')
            //       ->onDelete('cascade');

            // $table->foreign('id_anggota')
            //       ->references('id_anggota')
            //       ->on('anggota_kube')
            //       ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};