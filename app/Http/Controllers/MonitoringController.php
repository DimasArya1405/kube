<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monitoring;
use App\Models\JenisBantuan;
use App\Models\Kube;
use App\Models\Pendamping;

class MonitoringController extends Controller
{
    // ✅ TAMPIL DATA
    public function index()
    {
        $monitoring = Monitoring::with(['jenisBantuan','kube','pendamping'])->get();
        $jenis = JenisBantuan::all();
        $kube = Kube::all();
        $pendamping = Pendamping::all();

        return view('pendamping.dashboard.monitoringbantuan', compact('monitoring','jenis','kube','pendamping'));
    }

    
    // ✅ SIMPAN DATA
    public function store(Request $request)
{
    $request->validate([
        'id_jenis_bantuan' => 'required',
        'id_kube' => 'required',
        'id_pendamping' => 'required',
        'tanggal_monitoring' => 'required',
        'kesesuaian' => 'required'
    ]);

    $foto = null;

    if ($request->hasFile('foto_monitoring')) {
        $foto = $request->file('foto_monitoring')->store('monitoring', 'public');
    }

    Monitoring::create([
        'id_jenis_bantuan' => $request->id_jenis_bantuan,
        'id_kube' => $request->id_kube,
        'id_pendamping' => $request->id_pendamping,
        'tanggal_monitoring' => $request->tanggal_monitoring,
        'kesesuaian' => $request->kesesuaian,
        'catatan' => $request->catatan,
        'foto_monitoring' => $foto
    ]);

    return redirect()->back()->with('success','Berhasil ditambahkan');
}

    // ✅ DELETE
    public function delete($id)
    {
        $data = Monitoring::findOrFail($id);

        if($data->foto_monitoring){
            \Storage::disk('public')->delete($data->foto_monitoring);
        }

        $data->delete();

        return redirect()->back()->with('success','Data berhasil dihapus');
    }
}