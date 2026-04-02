<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembagianKoordinatorTable extends Migration
{
    public function up()
    {
        Schema::create('pembagian_koordinator', function (Blueprint $table) {
            $table->increments('id_pembagian_koor'); // Primary Key

            $table->unsignedInteger('id_koor'); // FK ke koordinator (sementara)
            $table->unsignedInteger('id_pendamping'); // FK ke pendamping (sementara)

            $table->enum('status', ['Aktif', 'Selesai']); // Status penugasan

            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            // Aktifkan ini kalau tabel sudah ada semua
            /*
            $table->foreign('id_koor')
                  ->references('id_koor')
                  ->on('koordinator')
                  ->onDelete('cascade');

            $table->foreign('id_pendamping')
                  ->references('id_pendamping')
                  ->on('pendamping')
                  ->onDelete('cascade');
            */
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembagian_koordinator');
    }
}