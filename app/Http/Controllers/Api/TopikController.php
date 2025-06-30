<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topik;
use App\Models\Level;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class TopikController extends Controller
{
    // GET /api/topik
    public function index()
    {
        $topik = Topik::with('level')->get();

        return response()->json([
            'success' => true,
            'message' => 'List semua topik',
            'data' => $topik
        ]);
    }

    // GET /api/topik/{id}
    public function show($id)
    {
        $topik = Topik::with('level')->find($id);

        if (!$topik) {
            return response()->json([
                'success' => false,
                'message' => 'Topik tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail topik',
            'data' => $topik
        ]);
    }

    // POST /api/topik
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id_level' => 'required|exists:level,id_level',
        'nama_topik' => 'required|string|max:255',
        'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $iconUrl = null;
    if ($request->hasFile('icon')) {
        $iconUrl = Cloudinary::upload(
            $request->file('icon')->getRealPath(),
            ['folder' => 'topik/icon']
        )->getSecurePath();
    }

    $topik = Topik::create([
        'id_level' => $request->id_level,
        'nama_topik' => $request->nama_topik,
        'icon' => $iconUrl,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Topik berhasil dibuat',
        'data' => $topik
    ], 201);
}

    // PUT /api/topik/{id}
   public function update(Request $request, $id)
{
    $topik = Topik::find($id);

    if (!$topik) {
        return response()->json([
            'success' => false,
            'message' => 'Topik tidak ditemukan'
        ], 404);
    }

    $validator = Validator::make($request->all(), [
        'id_level' => 'sometimes|required|exists:level,id_level',
        'nama_topik' => 'sometimes|required|string|max:255',
        'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $data = $request->only('id_level', 'nama_topik');

    if ($request->hasFile('icon')) {
        $iconUrl = Cloudinary::upload(
            $request->file('icon')->getRealPath(),
            ['folder' => 'topik/icon']
        )->getSecurePath();

        $data['icon'] = $iconUrl;
    }

    $topik->update($data);

    return response()->json([
        'success' => true,
        'message' => 'Topik berhasil diperbarui',
        'data' => $topik
    ]);
}

    // DELETE /api/topik/{id}
    public function destroy($id)
    {
        $topik = Topik::find($id);

        if (!$topik) {
            return response()->json([
                'success' => false,
                'message' => 'Topik tidak ditemukan'
            ], 404);
        }

        $topik->delete();

        return response()->json([
            'success' => true,
            'message' => 'Topik berhasil dihapus'
        ]);
    }

    // GET /api/topik/level/{id_level}
public function getByLevel($id_level)
{
    $topik = Topik::with('level')->where('id_level', $id_level)->get();

    if ($topik->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada topik untuk level ini'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Daftar topik berdasarkan level',
        'data' => $topik
    ]);
}

// GET /api/level/{id}/topik
public function getTopikByLevel($id)
{
    $level = Level::with('topiks')->find($id);

    if (!$level) {
        return response()->json([
            'success' => false,
            'message' => 'Level tidak ditemukan'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Topik dalam level',
        'data' => $level->topiks
    ]);
}


}
