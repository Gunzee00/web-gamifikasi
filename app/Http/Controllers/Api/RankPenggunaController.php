<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RankPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function updateOrGetRank()
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan'], 401);
    }

    // Hitung total bintang dari semua topik
    $totalBintang = \App\Models\SkorPengguna::where('id_user', $user->id_user)
        ->sum('jumlah_bintang');

    // Tentukan rank berdasarkan jumlah bintang
    if ($totalBintang >= 18) {
        $namaRank = 'Emas';
    } elseif ($totalBintang >= 12) {
        $namaRank = 'Perak';
    } elseif ($totalBintang >= 6) {
        $namaRank = 'Perunggu';
    } else {
        $namaRank = 'Perunggu'; // Atau bisa 'Belum Punya'
    }

    // Simpan atau update ke tabel rank_pengguna
    \App\Models\RankPengguna::updateOrCreate(
        ['id_user' => $user->id_user],
        [
            'total_bintang' => $totalBintang,
            'nama_rank' => $namaRank
        ]
    );

    return response()->json([
        'status' => 'success',
        'id_user' => $user->id_user,
        'total_bintang' => $totalBintang,
        'nama_rank' => $namaRank
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
