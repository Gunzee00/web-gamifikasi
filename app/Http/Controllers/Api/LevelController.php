<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;

class LevelController extends Controller
{
    

    // Tampilkan semua data level tanpa relasi mataPelajaran
public function index()
{
    $levels = Level::all();

    return response()->json([
        'message' => 'Daftar semua level.',
        'data' => $levels
    ], 200);
}

    // Simpan data level baru tanpa id_mataPelajaran
    public function store(Request $request)
    {
        $validated = $request->validate([
            'penjelasan_level' => 'required|string|max:255',
        ]);

        $level = Level::create($validated);

        return response()->json([
            'message' => 'Level berhasil ditambahkan.',
            'data' => $level
        ], 201);
    }

    // Tampilkan detail level
   public function show($id)
{
    $level = Level::find($id);

    if (!$level) {
        return response()->json([
            'message' => 'Level tidak ditemukan.'
        ], 404);
    }

    return response()->json([
        'message' => 'Detail level berhasil diambil.',
        'data' => $level
    ], 200);
}
// Update data level berdasarkan ID
public function update(Request $request, $id)
{
    $level = Level::find($id);

    if (!$level) {
        return response()->json([
            'message' => 'Level tidak ditemukan.'
        ], 404);
    }

    $validated = $request->validate([
        'penjelasan_level' => 'required|string|max:255',
    ]);

    $level->update($validated);

    return response()->json([
        'message' => 'Level berhasil diperbarui.',
        'data' => $level
    ], 200);
}


    // Hapus data level
  public function destroy($id)
{
    $level = Level::find($id);

    if (!$level) {
        return response()->json([
            'message' => 'Level tidak ditemukan.'
        ], 404);
    }

    $level->delete();

    return response()->json([
        'message' => 'Level berhasil dihapus.'
    ], 200);
}


}
