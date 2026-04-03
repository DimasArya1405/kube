<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_kube', function (Blueprint $table) {
            $table->id('id_pengajuan_kube');
            $table->integer('id_kube');
            $table->integer('id_user');
            $table->integer('id_jenis_bantuan');
            $table->integer('jumlah_bantuan');
            $table->string('tujuan_pengajuan', 100);
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_disetujui');
            $table->string('keterangan', 255);
            $table->enum('status_pengajuan', ['diajukan', 'menunggu', 'disetujui', 'ditolak', 'cair'])->default('diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kube');
    }
};
