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

            $table->unsignedBigInteger('id_pendamping');
            $table->unsignedBigInteger('id_kube');

            $table->date('tanggal_kunjungan');
            $table->time('waktu_kunjungan');

            $table->enum('tujuan_kunjungan', [
                'Monitoring',
                'Evaluasi',
                'Koordinasi',
                'Kunjungan Rutin'
            ]);

            $table->integer('kunjungan_ke');

            $table->timestamps();

            // // Foreign Key
            // $table->foreign('id_pendamping')
            //     ->references('id')
            //     ->on('pendamping')
            //     ->onDelete('cascade');

            // $table->foreign('id_kube')
            //     ->references('id')
            //     ->on('kube')
            //     ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan_pendamping');
    }
};
