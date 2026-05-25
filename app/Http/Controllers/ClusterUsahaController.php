<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClusterUsaha;

class ClusterUsahaController extends Controller
{
    public function index()
{
    $data = DB::table('cluster_usaha')
        ->join('kategori', 'cluster_usaha.id_kategori', '=', 'kategori.id_kategori')
        ->select('cluster_usaha.*', 'kategori.nama_kategori')
        ->get();

    $kategori = DB::table('kategori')->get();

    return view('admin.data_master.cluster', compact('data','kategori'));
}

    public function create()
    {
        $kategori = DB::table('kategori')->get();
        return view('cluster_usaha.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        DB::table('cluster_usaha')->insert([
            'nama_cluster' => $request->nama_cluster,
            'deskripsi' => $request->deskripsi,
            'id_kategori' => $request->id_kategori,
            'status' => $request->status,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect('/cluster_usaha')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = DB::table('cluster_usaha')->where('id_cluster', $id)->first();
        $kategori = DB::table('kategori')->get();

        return view('cluster_usaha.edit', compact('data', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        DB::table('cluster_usaha')->where('id_cluster', $id)->update([
            'nama_cluster' => $request->nama_cluster,
            'deskripsi' => $request->deskripsi,
            'id_kategori' => $request->id_kategori,
            'status' => $request->status,
            'updated_at' => now()
        ]);

        return redirect('/cluster_usaha')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        DB::table('cluster_usaha')->where('id_cluster', $id)->delete();

        return redirect('/cluster_usaha')->with('success', 'Data berhasil dihapus');
    }

    public function exportPDF()
    {
        $data = DB::table('cluster_usaha')
            ->join(
                'kategori',
                'cluster_usaha.id_kategori',
                '=',
                'kategori.id_kategori'
            )
            ->select(
                'cluster_usaha.*',
                'kategori.nama_kategori'
            )
            ->get();

        return Pdf::loadView(
            'admin.data_master.cluster_pdf',
            [
                'data' => $data,
                'isPdf' => true
            ]
        )->download('laporan_cluster_usaha.pdf');
    }

    public function exportExcel()
    {
        $data = DB::table('cluster_usaha')
            ->join(
                'kategori',
                'cluster_usaha.id_kategori',
                '=',
                'kategori.id_kategori'
            )
            ->select(
                'cluster_usaha.*',
                'kategori.nama_kategori'
            )
            ->get();

        $filename = "laporan_cluster_usaha.xls";

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");

        echo "<table border='1'>";

        echo "<tr>
                <th>No</th>
                <th>Nama Cluster</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Status</th>
            </tr>";

        $no = 1;

        foreach ($data as $row) {

            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row->nama_cluster}</td>
                    <td>{$row->nama_kategori}</td>
                    <td>{$row->deskripsi}</td>
                    <td>{$row->status}</td>
                </tr>";

            $no++;
        }

        echo "</table>";
        exit;
    }
}