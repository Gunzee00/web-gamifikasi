<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LevelController extends Controller
{
    /**
     * Constructor dengan middleware untuk akses terbatas.
     */
    public function __construct()
    {
        // Hanya admin dan super_admin yang boleh membuat data level
        $this->middleware('auth:sanctum');
        $this->middleware('role:admin,super_admin')->only(['store']);
    }

    /**
     * Menampilkan semua data level.
     */
    public function index()
    {
        $levels = Level::with('mataPelajaran')->get();
        return response()->json($levels, 200);
    }

    /**
     * Membuat data level baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_mataPelajaran' => 'required|exists:matapelajaran,id_mataPelajaran',
            'penjelasan_level' => 'required|string|max:255',
        ]);

        $level = Level::create($validated);

        return response()->json([
            'message' => 'Level berhasil ditambahkan.',
            'data' => $level
        ], 201);
    }

    /**
     * Menampilkan detail level tertentu.
     */
    public function show(Level $level)
    {
        return response()->json($level, 200);
    }

    /**
     * Mengupdate data level.
     */
    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'id_mataPelajaran' => 'required|exists:matapelajaran,id_mataPelajaran',
            'penjelasan_level' => 'required|string|max:255',
        ]);

        $level->update($validated);

        return response()->json([
            'message' => 'Level berhasil diperbarui.',
            'data' => $level
        ], 200);
    }

    /**
     * Menghapus data level.
     */
    public function destroy(Level $level)
    {
        $level->delete();

        return response()->json([
            'message' => 'Level berhasil dihapus.'
        ], 204);
    }

    public function getLevelsByMataPelajaran($id_mataPelajaran)
{
    $levels = Level::where('id_mataPelajaran', $id_mataPelajaran)
                ->with('mataPelajaran') // Mengambil juga informasi mata pelajaran
                ->get();

    if ($levels->isEmpty()) {
        return response()->json([
            'message' => 'Tidak ada level untuk mata pelajaran ini.'
        ], 404);
    }

    return response()->json($levels, 200);
}
}