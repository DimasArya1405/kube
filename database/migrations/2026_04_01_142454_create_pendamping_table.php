<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendamping', function (Blueprint $table) {
            $table->id('id_pendamping');

            $table->unsignedBigInteger('id_user');
            $table->string('nik', 16);
            $table->string('nama_pendamping', 100);

            $table->enum('jenis_kelamin', ['L', 'P']);

            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');

            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->string('email', 100);

            $table->string('pendidikan_terakhir', 50);

            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_desa');      // ← tambahan

            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();

            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');

            $table->string('foto', 255)->nullable();

            $table->timestamps();

            // Foreign Key - User
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');

            // Foreign Key - Kecamatan
            $table->foreign('id_kecamatan')
                ->references('id_kecamatan')
                ->on('kecamatan')
                ->onDelete('cascade');

            // Foreign Key - Desa
            $table->foreign('id_desa')
                ->references('id_desa')
                ->on('desa')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendamping');
    }
};