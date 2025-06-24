<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\JawabanPengguna;
use App\Models\SkorPengguna;
use App\Models\RekapSkorPengguna;
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

    $jumlahBenar = JawabanPengguna::where('id_user', $user->id_user)
        ->whereIn('id_soal', $soalIdsTopikIni)
        ->where('status', 'benar')
        ->take(9)
        ->count();

    // Hitung bintang
    if ($jumlahBenar >= 7 && $jumlahBenar <= 9) {
        $jumlahBintang = 3;
    } elseif ($jumlahBenar >= 4 && $jumlahBenar <= 6) {
        $jumlahBintang = 2;
    } elseif ($jumlahBenar >= 1 && $jumlahBenar <= 3) {
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

    return response()->json([
        'message' => 'Jawaban disimpan dan skor diperbarui',
        'jawaban' => $jawaban,
        'jumlah_benar' => $jumlahBenar,
        'jumlah_bintang' => $jumlahBintang
    ], 200);
}

    public function cekKelulusanTopik(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|integer',
            'id_topik' => 'required|integer',
        ]);

        $soalIds = Soal::where('id_topik', $validated['id_topik'])->pluck('id_soal');

        if ($soalIds->isEmpty()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Belum ada soal pada topik ini.',
                'boleh_lanjut' => false,
                'jumlah_benar' => 0
            ]);
        }

        $jumlahBenar = JawabanPengguna::where('id_user', $validated['id_user'])
            ->whereIn('id_soal', $soalIds)
            ->where('status', 'benar')
            ->count();

        $bolehLanjut = $jumlahBenar >= 3;

        return response()->json([
            'status' => $bolehLanjut ? 'success' : 'failed',
            'message' => $bolehLanjut
                ? 'Kamu bisa lanjut ke topik berikutnya.'
                : 'Minimal 3 soal benar untuk bisa lanjut ke topik berikutnya.',
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
            return response()->json(['message' => 'User tidak ditemukan.'], 401);
        }

        $skor = SkorPengguna::where('id_user', $user->id_user)
            ->where('id_topik', $id_topik)
            ->first();

        if (!$skor) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Skor untuk topik ini belum tersedia.',
                'jumlah_bintang' => 0
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
}
