<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('laporan_keuangan', function (Blueprint $table) {
        // Cek dulu, kalau kolom 'id_kube' BELUM ada, baru tambahkan
        if (!Schema::hasColumn('laporan_keuangan', 'id_kube')) {
            $table->unsignedBigInteger('id_kube')->nullable()->after('id_laporan');
            
            // Tambahkan foreign key-nya di sini
            $table->foreign('id_kube')->references('id_kube')->on('kube')->onDelete('cascade');
        }
    });
}

public function down()
{
    Schema::table('laporan_keuangan', function (Blueprint $table) {
        $table->dropForeign(['id_kube']);
        $table->dropColumn('id_kube');
    });
}

   
};
