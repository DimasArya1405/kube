<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbingan_kube_oleh_pendamping', function (Blueprint $table) {
            $table->id('id_bimbingan'); // primary key

            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_pendamping');
            $table->unsignedBigInteger('id_kube');

            $table->enum('jenis_bimbingan', [
                'Manajemen Usaha',
                'Pencatatan Keuangan',
                'Strategi Pemasaran',
                'Motivasi',
                'Mediasi'
            ]);

            $table->text('materi_bimbingan');
            $table->date('tanggal_bimbingan');
            $table->text('hasil_bimbingan')->nullable();
            $table->text('tindak_lanjut')->nullable();

            $table->enum('status_bimbingan', [
                'Terlaksana',
                'Dijadwalkan',
                'Ditunda'
            ]);

            $table->string('lampiran', 255)->nullable();

            $table->timestamps(); // created_at & updated_at

            // Foreign Key
            $table->foreign('id_pendamping')->references('id')->on('pembagian_pendamping')->onDelete('cascade');
            $table->foreign('id_kube')->references('id')->on('kube')->onDelete('cascade');

            // Kalau ada tabel jadwal, aktifkan ini:
            // $table->foreign('id_jadwal')->references('id')->on('jadwal')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbingan_kube_oleh_pendamping');
    }
};