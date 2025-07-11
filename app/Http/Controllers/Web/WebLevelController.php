<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Level;

class WebLevelController extends Controller
{
    // Tampilkan semua level
    public function index()
    {
        $levels = Level::all();

        return view('admin.level.level', [
            'title' => 'Web Level',
            'levels' => $levels
        ]);
    }

    // Simpan level baru (tanpa mata pelajaran)
    public function store(Request $request)
    {
        $request->validate([
            'nama_level' => 'required|string|max:255|unique:level,nama_level',
        ], [
            'nama_level.required' => 'Nama level wajib diisi.',
            'nama_level.unique' => 'Level dengan penjelasan ini sudah ada.',
        ]);

        Level::create([
            'nama_level' => $request->nama_level
        ]);

        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil ditambahkan.');
    }

    // Update level
    public function update(Request $request, $id)
    {
        $level = Level::findOrFail($id);

        $request->validate([
            'nama_level' => [
                'required',
                'string',
                'max:255',
                Rule::unique('level', 'nama_level')->ignore($id, 'id_level')
            ]
        ]);

        $level->update([
            'nama_level' => $request->nama_level
        ]);

        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil diperbarui.');
    }

    // Hapus level
    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil dihapus.');
    }
}
