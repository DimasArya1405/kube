<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monitoring;
use App\Models\JenisBantuan;
use App\Models\Kube;
use App\Models\Pendamping;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    // ✅ TAMPIL DATA
public function index()
{
    $user = auth()->user();
    $pendamping = \App\Models\Pendamping::where('email', $user->email)->first();
    $idPendamping = $pendamping ? $pendamping->id_pendamping : null;

    // UPDATE DI SINI: Tambahkan 'pencairan' ke dalam with()
    $monitoringList = \App\Models\Monitoring::with(['jenisBantuan', 'kube', 'pendamping', 'pencairan', 'pengajuan'])
    ->when($idPendamping, function($query) use ($idPendamping) {
        return $query->where('id_pendamping', $idPendamping);
    })
    ->get();

    // ... (sisa kode $sudahDimonitoring dan $pencairanTersedia biarkan sama) ...
    $sudahDimonitoring = \App\Models\Monitoring::pluck('id_pencairan')->toArray();

    $pencairanTersedia = \DB::table('pencairan_bantuan')
    ->join('pengajuan_kube', 'pencairan_bantuan.id_pengajuan', '=', 'pengajuan_kube.id_pengajuan_kube')
    ->join('kube', 'pengajuan_kube.id_kube', '=', 'kube.id_kube')
    ->join('jenis_bantuan', 'pengajuan_kube.id_jenis_bantuan', '=', 'jenis_bantuan.id_jenis_bantuan')
    ->join('pembagian_pendamping', 'kube.id_kube', '=', 'pembagian_pendamping.id_kube')
    ->select(
        'pencairan_bantuan.*', 
        'kube.nama_kube', 
        'jenis_bantuan.jenis_bantuan', 
        'pengajuan_kube.jumlah_bantuan'
    )
    ->where('pencairan_bantuan.status_pencairan', 'cair')
    ->where('pembagian_pendamping.id_pendamping', $idPendamping)
    ->whereNotIn('pencairan_bantuan.id_pencairan', $sudahDimonitoring)
    ->get();

    $jenis = \App\Models\JenisBantuan::all();

    return view('pendamping.dashboard.monitoringbantuan', compact('monitoringList', 'pencairanTersedia', 'jenis'));
}
    // ✅ SIMPAN DATA
    // ✅ SIMPAN DATA (Di dalam MonitoringController.php)
public function store(Request $request)
{
    // 1. Tambahkan proteksi: Cek apakah user yang login memiliki role 'pendamping'
    if (auth()->user()->role !== 'pendamping') {
        return redirect()->back()->with('error', 'Akses ditolak! Hanya pendamping yang dapat menambah data.');
    }

    // 2. Validasi input
    $request->validate([
        'id_pencairan'      => 'required', 
        'tanggal_monitoring' => 'required|date',
        'kesesuaian'         => 'required',
        'foto_monitoring'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // 3. Ambil info dari tabel pencairan berdasarkan id yang dipilih
    $pencairan = \DB::table('pencairan_bantuan')
        ->join('pengajuan_kube', 'pencairan_bantuan.id_pengajuan', '=', 'pengajuan_kube.id_pengajuan_kube')
        ->where('pencairan_bantuan.id_pencairan', $request->id_pencairan)
        ->first();

    // Pastikan data pencairan ditemukan untuk mencegah error
    if (!$pencairan) {
        return redirect()->back()->with('error', 'Data pencairan tidak ditemukan.');
    }

    // 4. Cari pendamping aktif untuk KUBE tersebut
    $pembagian = \DB::table('pembagian_pendamping')
        ->where('id_kube', $pencairan->id_kube)
        ->where('status', 'Aktif')
        ->first();

    $foto = $request->hasFile('foto_monitoring') ? $request->file('foto_monitoring')->store('monitoring', 'public') : null;

    // 5. Simpan data
    Monitoring::create([
        'id_pencairan'       => $request->id_pencairan,
        'id_jenis_bantuan'   => $pencairan->id_jenis_bantuan,
        'id_kube'            => $pencairan->id_kube,
        'id_pendamping'      => $pembagian ? $pembagian->id_pendamping : auth()->user()->pendamping->id_pendamping,
        'tanggal_monitoring' => $request->tanggal_monitoring,
        'kesesuaian'         => $request->kesesuaian,
        'catatan'            => $request->catatan,
        'foto_monitoring'    => $foto
    ]);

    return redirect()->back()->with('success', 'Data monitoring berhasil dibuat!');
}

    // ✅ DELETE
    public function delete($id)
    {
        $data = Monitoring::findOrFail($id);

        if ($data->foto_monitoring) {
            Storage::disk('public')->delete($data->foto_monitoring);
        }

        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    // ✅ UPDATE
   public function update(Request $request, $id)
{
    $data = Monitoring::findOrFail($id);

    $request->validate([
        'id_jenis_bantuan'   => 'required',
        'tanggal_monitoring' => 'required|date',
        'kesesuaian'         => 'required',
        'foto_monitoring'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // Handle foto
    $foto = $data->foto_monitoring; // Default foto lama
    if ($request->hasFile('foto_monitoring')) {
        if ($data->foto_monitoring) {
            Storage::disk('public')->delete($data->foto_monitoring);
        }
        $foto = $request->file('foto_monitoring')->store('monitoring', 'public');
    }

    $data->update([
        'id_jenis_bantuan'   => $request->id_jenis_bantuan,
        'tanggal_monitoring' => $request->tanggal_monitoring,
        'kesesuaian'         => $request->kesesuaian,
        'catatan'            => $request->catatan,
        'foto_monitoring'    => $foto
    ]);

    return redirect()->back()->with('success', 'Berhasil diupdate');
}

    // ✅ EXPORT PDF
    public function exportPdf()
{
    $user = auth()->user();
    $query = \App\Models\Monitoring::with(['jenisBantuan', 'kube', 'pendamping']);

    // Filter PDF berdasarkan akses
    if ($user->role === 'pendamping') {
        $pendamping = \App\Models\Pendamping::where('email', $user->email)->first();
        if ($pendamping) {
            $query->where('id_pendamping', $pendamping->id_pendamping);
        }
    }

    $monitoring = $query->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pendamping.dashboard.monitoring_pdf', compact('monitoring'))
                    ->setPaper('a4', 'landscape');

    return $pdf->download('laporan_monitoring_' . now()->format('Y-m-d') . '.pdf');
}

    public function create($id_pencairan)
{
    // Ambil data pencairan untuk ditampilkan di form
    $pencairan = \DB::table('pencairan_bantuan')
        ->join('pengajuan_kube', 'pencairan_bantuan.id_pengajuan', '=', 'pengajuan_kube.id_pengajuan_kube')
        ->join('kube', 'pengajuan_kube.id_kube', '=', 'kube.id_kube')
        ->where('pencairan_bantuan.id_pencairan', $id_pencairan)
        ->first();

    return view('pendamping.dashboard.create_monitoring', compact('pencairan'));
}
}