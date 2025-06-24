<?php

namespace App\Http\Controllers\Web;  
use App\Http\Controllers\Controller;

use App\Models\Topik;
use App\Models\Level;
use Illuminate\Http\Request;

class WebTopikController extends Controller
{
    // Tampilkan semua topik
    public function index()
{
     $topik = Topik::all();
    //  dd($levels->toArray());

    return view('admin.topik.topik', [
        'title' => 'Topik',
       'topik' =>  $topik
        ]);    
}
    // Tampilkan form tambah topik
    public function create()
    {
        $levels = Level::all();
        return view('topik.create', compact('levels'));
    }

    // Simpan topik baru
    public function store(Request $request)
    {
        $request->validate([
            'id_level' => 'required|exists:level,id_level',
            'nama_topik' => 'required|string|max:255',
        ]);

        Topik::create($request->only('id_level', 'nama_topik'));

        return redirect()->route('topik.index')->with('success', 'Topik berhasil ditambahkan.');
    }

    // Tampilkan detail topik
    public function show($id)
    {
        $topik = Topik::with('level')->findOrFail($id);
        return view('topik.show', compact('topik'));
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $topik = Topik::findOrFail($id);
        $levels = Level::all();
        return view('topik.edit', compact('topik', 'levels'));
    }

    // Update topik
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_level' => 'required|exists:level,id_level',
            'nama_topik' => 'required|string|max:255',
        ]);

        $topik = Topik::findOrFail($id);
        $topik->update($request->only('id_level', 'nama_topik'));

        return redirect()->route('topik.index')->with('success', 'Topik berhasil diperbarui.');
    }

    // Hapus topik
    public function destroy($id)
    {
        $topik = Topik::findOrFail($id);
        $topik->delete();

        return redirect()->route('topik.index')->with('success', 'Topik berhasil dihapus.');
    }

    
}
