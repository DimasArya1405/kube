<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Primary Key
            $table->id('id_user');

            // Role
            $table->enum('role', [
                'admin',
                'ketua_kube',
                'pendamping',
                'koordinator',
                'kepala_dinas'
            ]);

            // Data user
            $table->string('nama', 100);
            $table->string('email', 100);
            $table->string('password', 100);

            // Status
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            // Kontak & alamat
            $table->string('no_hp', 15)->nullable();
            $table->text('alamat')->nullable();

            // Relasi wilayah
            $table->unsignedBigInteger('id_kecamatan');
            $table->unsignedBigInteger('id_desa_kelurahan');

            // Identitas
            $table->string('nik', 30)->nullable();

            // Timestamp
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign Key
            $table->foreign('id_kecamatan')
                ->references('id_kecamatan')
                ->on('kecamatan')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_desa_kelurahan')
                ->references('id_desa_kelurahan')
                ->on('desa_kelurahan')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // ==========================================
        // Semua password diset: 12345678
        // ==========================================
        DB::table('users')->insert([
            [
                'id_user' => 1,
                'role' => 'ketua_kube',
                'nama' => 'Ran',
                'email' => 'ran123@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => 'aktif',
                'no_hp' => '081111111111',
                'alamat' => 'Jl. Mawar No. 1, Kota A',
                'nik' => '3201012345670001',
                'id_kecamatan' => 1, 
                'id_desa_kelurahan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 2,
                'role' => 'pendamping',
                'nama' => 'Rana',
                'email' => 'rana123@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => 'aktif',
                'no_hp' => '082222222222',
                'alamat' => 'Jl. Melati No. 2, Kota A',
                'nik' => '3201012345670002',
                'id_kecamatan' => 1, 
                'id_desa_kelurahan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 3,
                'role' => 'koordinator',
                'nama' => 'Rani',
                'email' => 'rani123@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => 'aktif',
                'no_hp' => '083333333333',
                'alamat' => 'Jl. Anggrek No. 3, Kota A',
                'nik' => '3201012345670003',
                'id_kecamatan' => 1, 
                'id_desa_kelurahan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 4,
                'role' => 'kepala_dinas',
                'nama' => 'Rane',
                'email' => 'rane123@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => 'aktif',
                'no_hp' => '084444444444',
                'alamat' => 'Jl. Kenanga No. 4, Kota A',
                'nik' => '3201012345670004',
                'id_kecamatan' => 1, 
                'id_desa_kelurahan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_user' => 5,
                'role' => 'admin',
                'nama' => 'Ranu',
                'email' => 'ranu123@gmail.com',
                'password' => Hash::make('12345678'),
                'status' => 'aktif',
                'no_hp' => '085555555555',
                'alamat' => 'Jl. Kamboja No. 5, Kota A',
                'nik' => '3201012345670005',
                'id_kecamatan' => 1, 
                'id_desa_kelurahan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};