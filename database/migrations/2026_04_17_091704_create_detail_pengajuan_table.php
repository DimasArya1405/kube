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
    Schema::create('detail_pengajuan', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('pengajuan_id');
    $table->unsignedBigInteger('id_jenis_bantuan');

    $table->string('nama_item');
    $table->integer('jumlah');

    $table->timestamps();

    $table->foreign('pengajuan_id')
          ->references('id_pengajuan_kube')
          ->on('pengajuan_kube')
          ->onDelete('cascade');
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengajuan');
    }
};
