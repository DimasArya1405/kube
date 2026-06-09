<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGaleriTable extends Migration
{
    public function up()
    {
        Schema::create('galeri', function (Blueprint $table) {

            $table->increments('id_galeri');

            $table->string('judul', 150);

            $table->string('gambar');

            $table->date('tanggal');

            $table->text('deskripsi')->nullable();

            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('galeri');
    }
}