<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendamping', function (Blueprint $table) {
            $table->id('id_pendamping'); // primary key auto increment
            
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
            
            $table->year('tahun_mulai');
            
            $table->enum('status', ['Aktif', 'Tidak Aktif']);
            
            $table->string('foto', 255)->nullable();
            
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            // Foreign Key
            $table->foreign('id_kecamatan')
                  ->references('id_kecamatan')
                  ->on('kecamatan')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendamping');
    }
};