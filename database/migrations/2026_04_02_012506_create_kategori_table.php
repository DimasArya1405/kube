<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriTable extends Migration
{
    public function up()
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->increments('id_kategori'); // Primary Key

            $table->string('nama_kategori', 100); // Nama kategori
            $table->text('deskripsi')->nullable(); // Deskripsi

            $table->string('status', 15); // Aktif / Nonaktif

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategori');
    }
}