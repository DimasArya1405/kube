<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan_pendamping', function (Blueprint $table) {
        $table->id('id_kunjungan');

        $table->unsignedBigInteger('id_pembagian');

        $table->date('tanggal_kunjungan');
        $table->time('waktu_kunjungan');

        $table->enum('tujuan_kunjungan', [
            'Monitoring',
            'Evaluasi',
            'Koordinasi',
            'Kunjungan Rutin'
        ]);

        $table->integer('kunjungan_ke');

        $table->text('catatan')->nullable(); 

        $table->timestamps();

        // FK
        $table->foreign('id_pembagian')
            ->references('id_pembagian')
            ->on('pembagian_pendamping')
            ->onDelete('cascade');
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan_pendamping');
    }
};
