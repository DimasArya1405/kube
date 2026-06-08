<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kube_pelatihan', function (Blueprint $table) {
            $table->id();
            
            // Tipe data INI HARUS SAMA PERSIS dengan Primary Key di tabel pelatihan dan kube
            $table->integer('id_pelatihan')->unsigned();
            $table->integer('id_kube')->unsigned();

            // Relasikan ke masing-masing tabel
            // $table->foreign('id_pelatihan')->references('id_pelatihan')->on('pelatihan')->onDelete('cascade');
            // // Catatan: Pastikan nama tabel kube kamu beneran 'kube' (bukan 'kubes')
            // $table->foreign('id_kube')->references('id_kube')->on('kube')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kube_pelatihan');
    }
};