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
        Schema::create('mitra', function (Blueprint $table) {
            $table->id('id_mitra');
            $table->string('nama_mitra', 100);
            $table->string('jenis_mitra', 20);
            $table->string('no_telp', 15);
            $table->string('email', 50);
            $table->text('alamat');
            $table->string('nama_pic', 50);
            $table->string('telp_pic', 15);
            $table->string('mou', 255);
            $table->date('tgl_mou');
            $table->integer('masa_berlaku');
            // Enum untuk status (Aktif/Tidak Aktif)
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra');
    }
};
