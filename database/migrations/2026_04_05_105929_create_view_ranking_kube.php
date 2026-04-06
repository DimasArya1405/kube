<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        DB::statement('DROP VIEW IF EXISTS view_ranking_kube');
        DB::statement('
            CREATE VIEW view_ranking_kube AS
            SELECT
                k.id_kube, k.nama_kube, k.status,
                d.id_desa_kelurahan, d.nama_desa_kelurahan,
                kec.id_kecamatan, kec.nama_kecamatan,
                cl.id_cluster, cl.nama_cluster,
                kat.id_kategori, kat.nama_kategori,
                l.periode_tahun AS tahun,
                SUM(l.total_omset) AS total_omset,
                SUM(l.total_pengeluaran) AS total_pengeluaran,
                SUM(l.laba_bersih) AS total_laba_bersih
            FROM laporan_keuangan l
            JOIN pengajuan_kube pk ON l.id_persetujuan = pk.id_pengajuan_kube
            JOIN kube k ON pk.id_kube = k.id_kube
            JOIN desa_kelurahan d ON k.id_desa_kelurahan = d.id_desa_kelurahan
            JOIN kecamatan kec ON d.id_kecamatan = kec.id_kecamatan
            JOIN cluster_usaha cl ON k.id_cluster = cl.id_cluster
            JOIN kategori kat ON cl.id_kategori = kat.id_kategori
            GROUP BY
                k.id_kube, k.nama_kube, k.status,
                d.id_desa_kelurahan, d.nama_desa_kelurahan,
                kec.id_kecamatan, kec.nama_kecamatan,
                cl.id_cluster, cl.nama_cluster,
                kat.id_kategori, kat.nama_kategori,
                l.periode_tahun
        ');
    }
    
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS view_ranking_kube');
    }

};