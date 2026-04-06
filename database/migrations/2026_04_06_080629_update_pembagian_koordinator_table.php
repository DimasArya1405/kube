<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembagian_koordinator', function (Blueprint $table) {

            // hapus kolom lama
            $table->dropColumn('id_pendamping');

            // tambah kolom baru TANPA FK
            $table->unsignedBigInteger('id_pembagian')->after('id_koor');
        });
    }

    public function down()
    {
        Schema::table('pembagian_koordinator', function (Blueprint $table) {

            // rollback
            $table->dropColumn('id_pembagian');
            $table->unsignedBigInteger('id_pendamping')->after('id_koor');
        });
    }
};