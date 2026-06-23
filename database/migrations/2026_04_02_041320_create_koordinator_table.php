<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koordinator', function (Blueprint $table) {
            $table->id('id_koor');
            $table->unsignedBigInteger('id_user');
            $table->string('nik', 16);
            $table->string('nama_koordinator', 100);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->string('email', 100);
            $table->string('pendidikan_terakhir', 50);
            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_desa_kelurahan')->nullable();
            $table->string('wilayah', 100)->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_kecamatan')->references('id_kecamatan')->on('kecamatan')->onDelete('cascade');
            $table->foreign('id_desa_kelurahan')->references('id_desa_kelurahan')->on('desa_kelurahan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koordinator');
    }
};