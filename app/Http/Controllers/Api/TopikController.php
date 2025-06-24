<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Topik;
use Illuminate\Support\Facades\Validator;

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
            'nama_topik' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $topik = Topik::create($request->only('id_level', 'nama_topik'));

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
            'nama_topik' => 'sometimes|required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $topik->update($request->only('id_level', 'nama_topik'));

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
}
