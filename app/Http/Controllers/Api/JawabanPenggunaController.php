<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\JawabanPengguna;
use App\Models\SkorPengguna;
use App\Models\RankPengguna;
use App\Models\RekapSkorPengguna;
use App\Models\Topik;

use App\Models\Soal;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class JawabanPenggunaController extends Controller
{
public function simpanJawaban(Request $request)
{
    \Log::info('User Auth:', ['user' => Auth::user()]);

    if (!Auth::user()) {
        return response()->json(['message' => 'User tidak ditemukan.'], 401);
    }

    $user = Auth::user();
    $soal = Soal::with('topik')->find($request->id_soal);

    if (!$soal) {
        return response()->json(['message' => 'Soal tidak ditemukan.'], 404);
    }

    $status = ($request->jawaban_siswa === $soal->jawabanBenar) ? 'benar' : 'salah';

    $jawaban = JawabanPengguna::updateOrCreate(
        ['id_user' => $user->id_user, 'id_soal' => $soal->id_soal],
        ['jawaban_siswa' => $request->jawaban_siswa, 'status' => $status]
    );

    // Ambil semua soal dengan id_topik yang sama
    $soalIdsTopikIni = Soal::where('id_topik', $soal->id_topik)->pluck('id_soal');
// Hitung jumlah soal benar dari topik ini
$jumlahBenar = JawabanPengguna::where('id_user', $user->id_user)
    ->whereIn('id_soal', $soalIdsTopikIni)
    ->where('status', 'benar')
    ->count(); // take(9) dihapus karena tidak relevan lagi

// Hitung bintang (total soal = 5)
if ($jumlahBenar >= 5 && $jumlahBenar <= 6) {
    $jumlahBintang = 3;
} elseif ($jumlahBenar >= 3 && $jumlahBenar <= 4) {
    $jumlahBintang = 2;
} elseif ($jumlahBenar >= 1 && $jumlahBenar <= 2) {
    $jumlahBintang = 1;
} else {
    $jumlahBintang = 0;
}
    // Simpan skor berdasarkan topik
    SkorPengguna::updateOrCreate(
        ['id_user' => $user->id_user, 'id_topik' => $soal->id_topik],
        [
            'jumlah_benar' => $jumlahBenar,
            'nama_topik' => $soal->topik->nama_topik ?? '',
            'jumlah_bintang' => $jumlahBintang,
        ]
    );

    // Hitung total bintang dari semua topik yang sudah dikerjakan user ini
    $totalBintang = SkorPengguna::where('id_user', $user->id_user)->sum('jumlah_bintang');

    // Tentukan nama rank berdasarkan total bintang
    if ($totalBintang >= 18) {
        $namaRank = 'Emas';
    } elseif ($totalBintang >= 12) {
        $namaRank = 'Perak';
    } elseif ($totalBintang >= 6) {
        $namaRank = 'Perunggu';
    } else {
        $namaRank = 'Perunggu'; // default kalau < 6
    }

    // Simpan ke tabel rank_pengguna
    \App\Models\RankPengguna::updateOrCreate(
        ['id_user' => $user->id_user],
        [
            'total_bintang' => $totalBintang,
            'nama_rank' => $namaRank,
        ]
    );

    return response()->json([
        'message' => 'Jawaban disimpan dan skor + rank diperbarui',
        'jawaban' => $jawaban,
        'jumlah_benar' => $jumlahBenar,
        'jumlah_bintang' => $jumlahBintang,
        'total_bintang' => $totalBintang,
        'nama_rank' => $namaRank
    ], 200);
}




    public function cekKelulusanTopik(Request $request)
{
    $validated = $request->validate([
        'id_user' => 'required|integer',
        'id_topik' => 'required|integer',
    ]);

    // Cek apakah ada soal untuk topik ini
    $adaSoal = Soal::where('id_topik', $validated['id_topik'])->exists();

    if (!$adaSoal) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Belum ada soal pada topik ini.',
            'boleh_lanjut' => false,
            'jumlah_bintang' => 0
        ]);
    }

    // Ambil skor pengguna untuk topik ini
    $skor = \App\Models\SkorPengguna::where('id_user', $validated['id_user'])
        ->where('id_topik', $validated['id_topik'])
        ->first();

    if (!$skor) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Kamu belum mengerjakan soal pada topik ini.',
            'boleh_lanjut' => false,
            'jumlah_bintang' => 0
        ]);
    }

    $bolehLanjut = $skor->jumlah_bintang >= 2;

    return response()->json([
        'status' => $bolehLanjut ? 'success' : 'failed',
        'message' => $bolehLanjut
            ? 'Kamu bisa lanjut ke topik berikutnya.'
            : 'Minimal 2 bintang diperlukan untuk lanjut ke topik berikutnya.',
        'boleh_lanjut' => $bolehLanjut,
        'jumlah_bintang' => $skor->jumlah_bintang
    ]);
}


    public function getSkorSaya()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 401);
        }

        $skor = SkorPengguna::where('id_user', $user->id_user)
            ->with('topik') // relasi ke Topik, bukan lagi level
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $skor
        ]);
    }

   public function getBintangSayaByTopik($id_topik)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'status' => 'unauthorized',
            'message' => 'User tidak ditemukan.'
        ], 401);
    }

    $skor = SkorPengguna::where('id_user', $user->id_user)
        ->where('id_topik', $id_topik)
        ->first();

    if (!$skor) {
        return response()->json([
            'status' => 'not_found',
            'message' => 'Skor untuk topik ini belum tersedia.',
            'jumlah_bintang' => 0,
            'jumlah_benar' => 0,
            'nama_topik' => null,
        ]);
    }

    return response()->json([
        'status' => 'success',
        'id_topik' => $id_topik,
        'jumlah_bintang' => $skor->jumlah_bintang,
        'jumlah_benar' => $skor->jumlah_benar,
        'nama_topik' => $skor->nama_topik,
    ]);
}
public function cekKelulusanLevel(Request $request)
{
    $validated = $request->validate([
        'id_user' => 'required|integer',
        'id_level' => 'required|integer',
    ]);

    // Ambil topik terakhir dalam level, berdasarkan id_topik terbesar
    $topikTerakhir = Topik::where('id_level', $validated['id_level'])
       ->orderBy('created_at', 'desc') // atau pakai orderBy('created_at', 'desc') jika tersedia
        ->first();

    if (!$topikTerakhir) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Tidak ada topik dalam level ini.',
            'boleh_lanjut_level' => false,
        ]);
    }

    // Cek skor pengguna untuk topik terakhir ini
    $skor = SkorPengguna::where('id_user', $validated['id_user'])
        ->where('id_topik', $topikTerakhir->id_topik)
        ->first();

    if (!$skor) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Kamu belum mengerjakan topik terakhir di level ini.',
            'boleh_lanjut_level' => false,
        ]);
    }

    $bolehLanjut = $skor->jumlah_bintang >= 2;

    return response()->json([
        'status' => $bolehLanjut ? 'success' : 'failed',
        'message' => $bolehLanjut
            ? 'Kamu sudah menyelesaikan topik terakhir di level ini dengan minimal 2 bintang. Level berikutnya bisa dibuka.'
            : 'Kamu belum mencapai minimal 2 bintang di topik terakhir level ini.',
        'boleh_lanjut_level' => $bolehLanjut,
        'topik_terakhir' => $topikTerakhir->nama_topik,
        'jumlah_bintang' => $skor->jumlah_bintang
    ]);
}
}
