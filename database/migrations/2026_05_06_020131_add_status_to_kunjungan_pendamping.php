<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kunjungan_pendamping', function (Blueprint $table) {
            $table->enum('status', ['terjadwal', 'selesai'])->default('terjadwal');
            $table->string('foto_bukti')->nullable();
            $table->text('catatan_hasil')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kunjungan_pendamping', function (Blueprint $table) {
            $table->dropColumn(['status', 'foto_bukti', 'catatan_hasil']);
        });
    }
};
