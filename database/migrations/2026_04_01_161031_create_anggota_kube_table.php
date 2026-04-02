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
        Schema::create('anggota_kube', function (Blueprint $table) {
            $table->id('id_anggota');
            
            $table->unsignedBigInteger('id_kube'); 
            
            $table->string('nama_anggota', 100);
            $table->string('nik', 16);
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->string('jabatan', 20);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_kube')
                  ->references('id_kube')
                  ->on('kube')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_kube');
    }
};