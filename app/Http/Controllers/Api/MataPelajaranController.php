<?php

namespace App\Http\Controllers\Api;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class MataPelajaranController extends Controller
{
    // Constructor untuk memeriksa token pada setiap request
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Menampilkan daftar semua subjek
    public function index()
    {
        $matapelajaran = MataPelajaran::select('id_mataPelajaran', 'nama_mataPelajaran')->get();
        return response()->json($matapelajaran);
    }
    

    // Menyimpan subjek baru
    public function store(Request $request)
    {
        try {
            // Validasi input dengan memastikan nama_matapelajaran unik
            $validated = $request->validate([
                'nama_mataPelajaran' => 'required|string|max:255|unique:mataPelajaran,nama_mataPelajaran',
            ], [
                'nama_mataPelajaran.required' => 'Nama mata pelajaran wajib diisi.',
                'nama_mataPelajaran.unique' => 'Nama mata pelajaran sudah ada.',
            ]);
    
            // Menyimpan data subjek
            $matapelajaran = MataPelajaran::create($validated);
    
            return response()->json([
                'message' => 'Data mata pelajaran berhasil ditambahkan.',
                'data' => $matapelajaran
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Data mata pelajaran gagal ditambahkan.',
                'errors' => $e->errors()
            ], 422);
        }
    }
    
    

    // Menampilkan detail subjek tertentu
    public function show(MataPelajaran $matapelajaran)
    {
        return response()->json($matapelajaran); // Mengembalikan data subjek dalam format JSON
    }

    public function update(Request $request, MataPelajaran $matapelajaran)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_mataPelajaran' => 'required|string|max:255',
        ]);

        // Update data mata Pelajaran
        $matapelajaran->update($validated);

        // Mengembalikan response JSON dengan pesan dan data yang telah diupdate
        return response()->json([
            'message' => 'Mata pelajaran berhasil diperbarui.',
            'data' => $matapelajaran
        ], 200);
    }

    /**
     * Menghapus subjek dari database.
     */
    public function destroy(MataPelajaran $matapelajaran)
    {
        // Menghapus subjek
        $matapelajaran->delete();

        // Mengembalikan response JSON dengan pesan sukses
        return response()->json([
            'message' => 'Mata Pelajaran berhasil dihapus.'
        ], 204);
    }

    /**
     * Mendapatkan data user yang terautentikasi.
     */
    public function authenticatedUser(Request $request)
    {
        // Mengembalikan data user yang terautentikasi
        return response()->json([
            'message' => 'Mata Pelajaran terautentikasi.',
            'user' => $request->user()
        ], 200);
    }
}