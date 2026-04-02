<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ranking_kube', function (Blueprint $table) {
            $table->increments('id_ranking');

            $table->unsignedInteger('id_kube');
            $table->unsignedInteger('id_laporan');
            $table->unsignedInteger('id_kecamatan');

            $table->year('tahun');

            $table->decimal('total_omset', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->decimal('total_laba_bersih', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranking_kube');
    }
};