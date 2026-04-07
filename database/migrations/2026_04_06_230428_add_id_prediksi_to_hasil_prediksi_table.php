<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hasil_prediksi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_prediksi')->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('hasil_prediksi', function (Blueprint $table) {
            $table->dropColumn('id_prediksi');
        });
    }
};