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
        Schema::create('pembagian_pendamping', function (Blueprint $table) {
            $table->id('id_pembagian');
            
            $table->unsignedBigInteger('id_kube');
            $table->unsignedBigInteger('id_pendamping');
            
            $table->date('tgl_pembagian')->nullable();
            $table->string('status', 15)->default('Aktif');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_kube')
                  ->references('id_kube')
                  ->on('kube')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            // $table->foreign('id_pendamping')
            //       ->references('id_pendamping')
            //       ->on('pendamping')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembagian_pendamping');
    }
};