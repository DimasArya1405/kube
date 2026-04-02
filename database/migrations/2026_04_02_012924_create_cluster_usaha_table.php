<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClusterUsahaTable extends Migration
{
    public function up()
    {
        Schema::create('cluster_usaha', function (Blueprint $table) {
            $table->increments('id_cluster'); // Primary Key

            $table->string('nama_cluster', 100); // Nama cluster usaha
            $table->text('deskripsi')->nullable(); // Deskripsi

            $table->unsignedInteger('id_kategori'); // FK ke kategori (sementara tanpa constraint)

            $table->enum('status', ['Aktif', 'Tidak Aktif']); // Status cluster

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            // 🔥 Tambahkan ini kalau tabel kategori sudah ada
            $table->foreign('id_kategori')
                  ->references('id_kategori')
                  ->on('kategori')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cluster_usaha');
    }
}