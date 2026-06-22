<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kube;
use App\Models\Keuangan;
use App\Models\ClusterUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Exports\KeuanganExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; //  Benar

class KeuanganController extends Controller
{
    public function index(Request $request) 
    {
        $userId = auth()->id(); 
        $user = auth()->user();
        
        $clusters = ClusterUsaha::all();
        $selectedKubeId = $request->query('id_kube');
        $daftarKube = Kube::all(); 
        $kubeMilikSaya = null;
        $searchResult = null;
        $search = $request->query('search');
        $filterBulan = $request->query('bulan');
        $filterTahun = $request->query('tahun');
   if ($user->role === 'admin' && $request->filled('search')) {
            $searchResult = Kube::where('nama_kube', 'like', '%' . $search . '%')->get();
        }

       
        $query = Keuangan::with(['cluster', 'kube']);

        if ($user->role === 'admin') {
            if ($selectedKubeId) {
                $query->where('id_kube', $selectedKubeId);
            } else {
                    if (!$request->filled('search') && !$filterBulan && !$filterTahun) {
                    $query->whereNull('id_laporan'); 
                }
            }
        } else {
               $kubeMilikSaya = Kube::where('id_user', $userId)->first();
            
            if ($kubeMilikSaya) {
                $query->where('id_kube', $kubeMilikSaya->id_kube);
                $selectedKubeId = $kubeMilikSaya->id_kube;
            } else {
                $query->whereNull('id_laporan');
            }
        }

        if ($request->filled('search')) {
            $searchLower = strtolower(trim($search));
            
            $bulanKeAngka = [
                'jan' => 1, 'januari' => 1, 'january' => 1,
                'feb' => 2, 'februari' => 2, 'february' => 2,
                'mar' => 3, 'maret' => 3, 'march' => 3,
                'apr' => 4, 'april' => 4,
                'mei' => 5, 'may' => 5,
                'jun' => 6, 'juni' => 6, 'june' => 6,
                'jul' => 7, 'juli' => 7, 'july' => 7,
                'agu' => 8, 'agustus' => 8, 'august' => 8, 'aug' => 8,
                'sep' => 9, 'september' => 9,
                'okt' => 10, 'oktober' => 10, 'october' => 10, 'oct' => 10,
                'nov' => 11, 'november' => 11,
                'des' => 12, 'desember' => 12, 'december' => 12, 'dec' => 12
            ];

            $targetAngkaBulan = isset($bulanKeAngka[$searchLower]) ? $bulanKeAngka[$searchLower] : null;

            $query->where(function($q) use ($search, $targetAngkaBulan) {
                $q->where('progres_keuangan', 'like', '%' . $search . '%')
                  ->orWhere('periode_tahun', 'like', '%' . $search . '%');

                if ($targetAngkaBulan !== null) {
                    $q->orWhere('periode_bulan', $targetAngkaBulan);
                }

                if (is_numeric($search)) {
                    $q->orWhere('periode_bulan', (int)$search);
                }

                $q->orWhereHas('kube', function($kubeQuery) use ($search) {
                    $kubeQuery->where('nama_kube', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->filled('bulan')) {
            $bulanDropdownMap = [
                'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4, 'Mei' => 5, 'Juni' => 6,
                'Juli' => 7, 'Agustus' => 8, 'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12
            ];
            
            $valueBulan = isset($bulanDropdownMap[$filterBulan]) ? $bulanDropdownMap[$filterBulan] : $filterBulan;
            $query->where('periode_bulan', $valueBulan);
        }
        
        if ($request->filled('tahun')) {
            $query->where('periode_tahun', $filterTahun);
        }

        $laporan = $query->orderBy('periode_tahun', 'desc')
                        ->orderBy('periode_bulan', 'desc')
                        ->get();

        $totalOmset = $laporan->sum('omset_pendapatan');
        $totalLaba = $laporan->sum('laba_bersih');
        $latest = $laporan->first();
        $perkembangan = $latest ? $latest->progres_keuangan : "Belum ada Data";

         $kubeDisetujui = DB::table('kube')
            ->when($user->role !== 'admin', function($q) use ($userId) {
                return $q->where('id_user', $userId);
            })
            ->select('id_kube', 'nama_kube')
            ->get();

        $payload = compact(
            'laporan', 'clusters', 'kubeDisetujui', 'totalOmset', 
            'totalLaba', 'perkembangan', 'daftarKube', 'selectedKubeId', 'searchResult'
        );

        if ($user->role === 'admin') {
            return view('admin.monevbimbingan.laporan_keuangan', $payload);
        } else {
            $payload['kubeMilikSaya'] = $kubeMilikSaya;
            return view('ketua_kube.monevbimbingan.laporan_keuangan', $payload);
        }
    }

    public function store(Request $request) 
    {
        $userId = auth()->id();
        $user = auth()->user();
      $request->validate([
            'id_kube' => 'required',
            'id_cluster' => 'required',
            'omset_pendapatan' => 'required|numeric|min:0',
            'total_pengeluaran' => 'required|numeric|min:0',
            'periode_bulan' => 'required|integer|between:1,12',
            'periode_tahun' => 'required|integer',
            'tanggal_laporan' => 'required|date'
        ]);
        $kube = Kube::where('id_kube', $request->id_kube)
            ->when($user->role !== 'admin', function($q) use ($userId) {
                return $q->where('id_user', $userId);
            })
            ->first();
        
        if (!$kube) {
            return redirect()->back()->with('error', 'Akses ditolak! Anda tidak memiliki wewenang pada kelompok KUBE ini.');
        }

        $laba = $request->omset_pendapatan - $request->total_pengeluaran;
        
        $lalu = Keuangan::where('id_kube', $kube->id_kube)
            ->orderBy('periode_tahun', 'desc')
            ->orderBy('periode_bulan', 'desc')
            ->first();

        $progres = 'Tetap';
        if ($lalu) {
            if ($laba > $lalu->laba_bersih) $progres = 'Meningkat';
            elseif ($laba < $lalu->laba_bersih) $progres = 'Menurun';
        }

        $data = $request->all();
        $data['id_kube'] = $kube->id_kube;
        $data['laba_bersih'] = $laba;
        $data['total_omset'] = $request->omset_pendapatan; 
        $data['progres_keuangan'] = $progres;
        $data['status_validasi'] = 'Draft';

        if ($request->hasFile('lampiran_keuangan')) {
            $file = $request->file('lampiran_keuangan');
            $namaFile = time() . "_" . str_replace(' ', '_', $file->getClientOriginalName());
            $file->move(public_path('uploads/keuangan'), $namaFile);
            $data['lampiran_keuangan'] = $namaFile;
        }

        Keuangan::create($data);
        
        return redirect()->back()->with('success', 'Laporan berhasil disimpan!');
    }

    public function update(Request $request, $id) 
    {
        $laporan = Keuangan::findOrFail($id);
        if (Auth::user()->role !== 'admin' && $laporan->kube->id_user !== Auth::id()) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $laba = $request->omset_pendapatan - $request->total_pengeluaran;
        
        $updateData = $request->except(['lampiran_keuangan']);
        $updateData['laba_bersih'] = $laba;
        $updateData['total_omset'] = $request->omset_pendapatan;

        if ($request->hasFile('lampiran_keuangan')) {
            if ($laporan->lampiran_keuangan && File::exists(public_path('uploads/keuangan/'.$laporan->lampiran_keuangan))) {
                File::delete(public_path('uploads/keuangan/'.$laporan->lampiran_keuangan));
            }
            $file = $request->file('lampiran_keuangan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/keuangan'), $filename);
            $updateData['lampiran_keuangan'] = $filename;
        }

        $laporan->update($updateData);
        return redirect()->back()->with('success', 'Laporan diperbarui!');
    }

    public function destroy($id) 
    {
        $l = Keuangan::findOrFail($id);
        if (Auth::user()->role !== 'admin' && $l->kube->id_user !== Auth::id()) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }
        
        if($l->lampiran_keuangan && File::exists(public_path('uploads/keuangan/'.$l->lampiran_keuangan))) {
            File::delete(public_path('uploads/keuangan/'.$l->lampiran_keuangan));
        }
        
        $l->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function exportAllPdf(Request $request)
    {
        $user = auth()->user();
        $query = Keuangan::with(['kube', 'cluster']);

        if ($user->role !== 'admin') {
            $query->whereHas('kube', function($q) use ($user) {
                $q->where('id_user', $user->id);
            });
        } elseif ($request->id_kube) {
            $query->where('id_kube', $request->id_kube);
        }

        $laporan = $query->orderBy('periode_tahun', 'desc')->get();
        $pdf = PDF::loadView('admin.monevbimbingan.rekap_resmi', compact('laporan'));
        return $pdf->stream('Rekapitulasi_Keuangan.pdf');
    }

    public function exportAllExcel()
    {
        return Excel::download(new KeuanganExport, 'Rekapitulasi_Keuangan.xlsx');
    }

    public function cetak($id)
    {
        $laporan = Keuangan::with(['kube', 'cluster'])->findOrFail($id);
        return view('ketua_kube.monevbimbingan.cetak', compact('laporan'));
    }

    public function exportExcelAll()
    {
        return Excel::download(new KeuanganExport(), 'Rekap_Semua_Laporan_Keuangan_KUBE.xlsx');
    }

    public function exportPdfAll()
    {
        $laporans = Keuangan::with(['kube', 'cluster'])->orderBy('tanggal_laporan', 'desc')->get();
        $namaKube = 'Semua KUBE';
        
        $pdf = Pdf::loadView('admin.monevbimbingan.laporan_pdf', compact('laporans', 'namaKube'))->setPaper('a4', 'landscape');
        return $pdf->download('Rekap_Semua_Laporan_Keuangan_KUBE.pdf');
    }

    public function exportExcelSingle($id_kube)
    {
        $kube = Kube::findOrFail($id_kube);
        $fileName = 'Laporan_Excel_' . str_replace(' ', '_', $kube->nama_kube) . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new KeuanganExport($id_kube), $fileName);
    }

    public function exportPdfSingle($id_kube)
    {
        $kube = Kube::findOrFail($id_kube);
        $laporans = Keuangan::with(['kube', 'cluster'])
            ->where('id_kube', $id_kube) 
            ->orderBy('tanggal_laporan', 'desc')
            ->get();

        $namaKube = $kube->nama_kube;
        $pdf = Pdf::loadView('admin.monevbimbingan.laporan_pdf', compact('laporans', 'namaKube'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_PDF_' . str_replace(' ', '_', $namaKube) . '.pdf');
    }

    public function exportPdfDetail($id)
    {
        $laporanTunggal = Keuangan::with(['kube', 'cluster'])->findOrFail($id);
        
        $namaKube = $laporanTunggal->kube->nama_kube ?? 'KUBE';
        $periode = $laporanTunggal->periode_bulan . '_' . $laporanTunggal->periode_tahun;
        $laporans = collect([$laporanTunggal]);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.monevbimbingan.laporan_pdf', compact('laporans', 'namaKube'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan_Detail_' . str_replace(' ', '_', $namaKube) . '_' . $periode . '.pdf');
    }
}