<?php

namespace App\Http\Controllers;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GaleriController extends Controller
{
    public function index()
{
    $totalGaleri = Galeri::count();

    $galeri = Galeri::latest()->get();

    return view('admin.galeri.index', compact('galeri', 'totalGaleri'));
}

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'gambar' => 'required|image',
        ]);

        $gambar = time() . '.' . $request->gambar->extension();

        $request->gambar->move(storage_path('app/public/images'), $gambar);

        Galeri::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambar,
            'tanggal' => now()->format('Y-m-d'),
        ]);

        return redirect()->back()->with('success', 'Galeri berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $galeri = Galeri::findOrFail($id);

        if ($request->hasFile('gambar')) {

            $gambar = time() . '.' . $request->gambar->extension();

            $request->gambar->move(public_path('images'), $gambar);

            $galeri->gambar = $gambar;
        }

        $galeri->judul = $request->judul;
        $galeri->deskripsi = $request->deskripsi;

        $galeri->save();

        return redirect()->back()->with('success', 'Galeri berhasil diupdate');
    }

    public function destroy($id)
    {
        $galeri = Galeri::findOrFail($id);

        $galeri->delete();

        return redirect()->back()->with('success', 'Galeri berhasil dihapus');
    }
    public function show($id)
    {
        $galeri = Galeri::findOrFail($id);

        return view('admin.galeri.detail', compact('galeri'));
    }
}