<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_statistik', function (Blueprint $table) {
            $table->increments('id_statistik');
            $table->year('tahun')->unique();
            $table->unsignedSmallInteger('total_kube');
            $table->unsignedSmallInteger('total_kube_aktif');
            $table->unsignedSmallInteger('total_kube_tidak_aktif');
            $table->decimal('pertumbuhan_persen', 5, 2)->default(0.00);
            $table->unsignedSmallInteger('jumlah_kecamatan');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_statistik');
    }
};