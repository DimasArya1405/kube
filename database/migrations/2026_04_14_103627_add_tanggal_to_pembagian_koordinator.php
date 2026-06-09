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
    Schema::table('pembagian_koordinator', function (Blueprint $table) {
        $table->date('tgl_mulai')->nullable();
        $table->date('tgl_selesai')->nullable();
    });
}

public function down()
{
    Schema::table('pembagian_koordinator', function (Blueprint $table) {
        $table->dropColumn(['tgl_mulai', 'tgl_selesai']);
    });
}
};
