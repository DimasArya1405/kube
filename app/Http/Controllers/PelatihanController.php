<?php

namespace App\Http\Controllers;

use App\Models\Pelatihan;
use App\Models\Kube;
use App\Models\Mitra;
use App\Models\Pendamping;
use Illuminate\Http\Request;
use App\Exports\PelatihanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PelatihanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        // UBAH 'kube' jadi 'kubes' karena relasinya sekarang Many-to-Many
        $pelatihans = Pelatihan::with(['kubes', 'pendamping', 'mitra'])
            ->when($search, function ($query, $search) {
                return $query->where('nama_pelatihan', 'like', '%' . $search . '%')
                    ->orWhereHas('kubes', function ($q) use ($search) { // UBAH ke 'kubes'
                        $q->where('nama_kube', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('pendamping', function ($q) use ($search) {
                        $q->where('nama_pendamping', 'like', '%' . $search . '%');
                    });
            })
            ->get();

        $kubes = Kube::all();
        $pendampings = Pendamping::all();
        $mitras = Mitra::all();

        return view('admin.monevbimbingan.pelatihan', compact('pelatihans', 'kubes', 'pendampings', 'mitras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string|max:150',
            'id_kube' => 'required|array', // Validasi pastikan id_kube adalah array
            'tanggal_mulai' => 'required|date',
            'status' => 'required'
        ]);

        // 1. Buat data pelatihan, TAPI KECUALIKAN id_kube karena tidak ada di tabel pelatihan
        $pelatihan = Pelatihan::create($request->except('id_kube'));

        // 2. Hubungkan banyak KUBE ke pelatihan ini lewat tabel pivot
        if ($request->has('id_kube')) {
            $pelatihan->kubes()->attach($request->id_kube);
        }

        return redirect()->back()->with('success', 'Data pelatihan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        // 1. Update data pelatihannya, kecualikan id_kube
        $pelatihan->update($request->except('id_kube'));

        // 2. Sinkronisasi KUBE yang baru (otomatis update ke tabel pivot)
        if ($request->has('id_kube')) {
            $pelatihan->kubes()->sync($request->id_kube);
        } else {
            // Kalau misal pas edit user gak milih KUBE sama sekali
            $pelatihan->kubes()->detach();
        }

        return redirect()->back()->with('success', 'Data pelatihan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        // Karena relasi FK di database kamu hapus, kita detach manual biar tabel pivot bersih
        $pelatihan->kubes()->detach();

        $pelatihan->delete();

        return redirect()->back()->with('success', 'Data pelatihan berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new PelatihanExport, 'data-pelatihan.xlsx');
    }

    public function exportPdf()
    {
        // Pastikan with()-nya memanggil 'kubes', bukan 'kube'
        $pelatihans = Pelatihan::with(['kubes', 'pendamping'])->get();
        $pdf = Pdf::loadView('admin.monevbimbingan.pelatihan_pdf', compact('pelatihans'));
        return $pdf->download('data-pelatihan.pdf');
    }
}
