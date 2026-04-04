<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('koordinator', function (Blueprint $table) {
            $table->id('id_koor');
            $table->unsignedBigInteger('id_kecamatan');

            $table->string('nama_koor', 100);
            $table->string('nik', 30);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->date('tgl_mulai');
            $table->enum('status', ['aktif', 'non-aktif']);
            $table->string('foto', 255)->nullable();

            $table->timestamps();

            // FOREIGN KEY
            $table->foreign('id_kecamatan')
                  ->references('id_kecamatan')
                  ->on('kecamatan')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koordinator');
    }
};