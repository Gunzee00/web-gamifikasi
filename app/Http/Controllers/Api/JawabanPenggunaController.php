<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\JawabanPengguna;
use App\Models\SkorPengguna;
use App\Models\RekapSkorPengguna;
use App\Models\Soal;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // tambahkan di atas jika belum


class JawabanPenggunaController extends Controller
{

public function simpanJawaban(Request $request)
{
    \Log::info('User Auth:', ['user' => Auth::user()]);

    if (!Auth::user()) {
        return response()->json(['message' => 'User tidak ditemukan.'], 401);
    }

    $user = Auth::user();
    $soal = Soal::find($request->id_soal);

    if (!$soal) {
        return response()->json(['message' => 'Soal tidak ditemukan.'], 404);
    }

    $status = ($request->jawaban_siswa === $soal->jawabanBenar) ? 'benar' : 'salah';

    // Simpan/update jawaban
    $jawaban = JawabanPengguna::updateOrCreate(
        ['id_user' => $user->id_user, 'id_soal' => $soal->id_soal],
        ['jawaban_siswa' => $request->jawaban_siswa, 'status' => $status]
    );

    // Hitung jumlah benar pada level ini, maksimal 9
    $soalIdsLevelIni = Soal::where('id_level', $soal->id_level)->pluck('id_soal');

    $jumlahBenar = JawabanPengguna::where('id_user', $user->id_user)
        ->whereIn('id_soal', $soalIdsLevelIni)
        ->where('status', 'benar')
        ->take(9)
        ->count();

   
    // Hitung bintang sesuai jumlah benar
if ($jumlahBenar >= 7 && $jumlahBenar <= 9) {
    $jumlahBintang = 3;
} elseif ($jumlahBenar >= 4 && $jumlahBenar <= 6) {
    $jumlahBintang = 2;
} elseif ($jumlahBenar >= 1 && $jumlahBenar <= 3) {
    $jumlahBintang = 1;
} else {
    $jumlahBintang = 0;
}

    // Ambil data level dari soal (pastikan ada relasi `level()` di model Soal)
    $level = $soal->level;

    // Simpan atau update skor pengguna
    SkorPengguna::updateOrCreate(
        ['id_user' => $user->id_user, 'id_level' => $soal->id_level],
        [
            'jumlah_benar' => $jumlahBenar,
            'nama_level' => $level->nama_level ?? '',
            'jumlah_bintang' => $jumlahBintang,
        ]
    );

    return response()->json([
        'message' => 'Jawaban disimpan dan skor diperbarui',
        'jawaban' => $jawaban,
        'jumlah_benar' => $jumlahBenar,
        'jumlah_bintang' => $jumlahBintang
    ], 200);
}


    public function cekKelulusanLevel(Request $request)
{
    $validated = $request->validate([
        'id_user' => 'required|integer',
        'id_level' => 'required|integer',
    ]);

    // Ambil semua soal di level ini
    $soalIds = Soal::where('id_level', $validated['id_level'])->pluck('id_soal');

    if ($soalIds->isEmpty()) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Belum ada soal pada level ini.',
            'boleh_lanjut' => false,
            'jumlah_benar' => 0
        ]);
    }

    // Hitung jawaban benar dari user
    $jumlahBenar = JawabanPengguna::where('id_user', $validated['id_user'])
        ->whereIn('id_soal', $soalIds)
        ->where('status', 'benar')
        ->count();

    $bolehLanjut = $jumlahBenar >= 3;

    return response()->json([
        'status' => $bolehLanjut ? 'success' : 'failed',
        'message' => $bolehLanjut
            ? 'Kamu bisa lanjut ke level berikutnya.'
            : 'Minimal 3 soal benar untuk bisa lanjut ke level berikutnya.',
        'boleh_lanjut' => $bolehLanjut,
        'jumlah_benar' => $jumlahBenar
    ]);
}

public function getSkorSaya()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan.'], 401);
    }

    $skor = SkorPengguna::where('id_user', $user->id_user)
        ->with('level') // pastikan relasi 'level' ada di model SkorPengguna
        ->get();

    return response()->json([
        'status' => 'success',
        'data' => $skor
    ]);
}

public function getBintangSayaByLevel($id_level)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan.'], 401);
    }

    $skor = SkorPengguna::where('id_user', $user->id_user)
        ->where('id_level', $id_level)
        ->first();

    if (!$skor) {
        return response()->json([
            'status' => 'not_found',
            'message' => 'Skor untuk level ini belum tersedia.',
            'jumlah_bintang' => 0
        ]);
    }

    return response()->json([
        'status' => 'success',
        'id_level' => $id_level,
        'jumlah_bintang' => $skor->jumlah_bintang,
        'jumlah_benar' => $skor->jumlah_benar,
        'nama_level' => $skor->nama_level,
    ]);
}



}