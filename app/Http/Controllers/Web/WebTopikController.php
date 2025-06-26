<?php

namespace App\Http\Controllers\Web;  
use App\Http\Controllers\Controller;

use App\Models\Topik;
use App\Models\Level;
use Illuminate\Http\Request;

class WebTopikController extends Controller
{
   public function index()
{
    $topiks = Topik::with('level')->get();
    $levels = Level::all();
    $title = 'Manajemen Topik'; // <-- tambahkan ini

    return view('admin.topik.topik', compact('topiks', 'levels', 'title'));
}
    public function store(Request $request)
    {
        $request->validate([
            'id_level' => 'required|exists:level,id_level',
            'nama_topik' => 'required|string|max:255',
        ]);

        Topik::create($request->only('id_level', 'nama_topik'));
        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $topik = Topik::findOrFail($id);

        $request->validate([
            'id_level' => 'required|exists:level,id_level',
            'nama_topik' => 'required|string|max:255',
        ]);

        $topik->update($request->only('id_level', 'nama_topik'));
        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $topik = Topik::findOrFail($id);
        $topik->delete();
        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil dihapus.');
    }


    public function showByLevel($id_level)
{
    $level = Level::findOrFail($id_level);
    $topiks = Topik::where('id_level', $id_level)->get();
    $title =   $level->nama_level;

    return view('admin.soal.topik', compact('topiks', 'level', 'title'));
}

public function createByTopik($id_topik)
{
    $topik = \App\Models\Topik::with('level')->findOrFail($id_topik);

    return view('admin.soal.create', [
        'title' => 'Tambah Soal untuk Topik ' . $topik->nama_topik,
        'topik' => $topik,
        'level' => $topik->level, // opsional kalau masih mau pakai id_level
    ]);
}


}