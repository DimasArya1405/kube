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
        Schema::create('kube', function (Blueprint $table) {
            $table->id('id_kube');
            $table->string('nama_kube', 100);
            $table->unsignedBigInteger('id_desa_kelurahan');
            $table->unsignedBigInteger('id_cluster');
            $table->date('tanggal_terbentuk')->nullable(); // Boleh kosong di awal
            $table->string('status', 15)->default('Menunggu'); // Default status
            $table->text('keterangan');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_desa_kelurahan')
                ->references('id_desa_kelurahan')
                ->on('desa_kelurahan')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // $table->foreign('id_cluster')
            //     ->references('id_cluster')
            //     ->on('cluster')
            //     ->onDelete('cascade')
            //     ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kube');
    }
};
