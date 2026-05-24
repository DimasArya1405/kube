<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
public function up(): void
{
    Schema::create('pertanyaan', function (Blueprint $table) {
        $table->id();
        $table->string('pertanyaan', 255);
        $table->timestamps();
    });

    // Menambahkan data langsung
   DB::table('pertanyaan')->insert([
        ['pertanyaan' => 'Apakah usaha KUBE masih berjalan?'],
        ['pertanyaan' => 'Apakah usaha KUBE memiliki potensi berkelanjutan?'],
        ['pertanyaan' => 'Apakah kelompok KUBE masih solid?'],
        ['pertanyaan' => 'Apakah usaha KUBE menghasilkan keuntungan?'],
        ['pertanyaan' => 'Apakah usaha KUBE mengalami perkembangan usaha?'],
        ['pertanyaan' => 'Apakah KUBE aktif melaporkan perkembangannya?'],
    ]);
}

    public function down(): void
    {
        Schema::dropIfExists('pertanyaan');
    }
};