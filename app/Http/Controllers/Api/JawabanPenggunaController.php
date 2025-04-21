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

    if (!$soal->level || !$soal->level->mataPelajaran) {
        return response()->json(['message' => 'Mata Pelajaran tidak ditemukan dalam soal.'], 404);
    }

    $id_mataPelajaran = $soal->level->id_mataPelajaran;

    // Mapping tipeSoal
    $tipeSoalOriginal = strtolower($soal->tipeSoal);
    $tipeSoal = match (true) {
        str_contains($tipeSoalOriginal, 'visual') => 'visual',
        str_contains($tipeSoalOriginal, 'auditori') => 'auditori',
        str_contains($tipeSoalOriginal, 'kinestetik') => 'kinestetik',
        default => $tipeSoalOriginal,
    };

    // Cek apakah user sudah menjawab soal ini sebelumnya
    $jawaban = JawabanPengguna::where('id_user', $user->id_user)
        ->where('id_soal', $soal->id_soal)
        ->first();

    $status = ($request->jawaban_siswa === $soal->jawabanBenar) ? 'benar' : 'salah';

    if ($jawaban) {
        $jawaban->update([
            'jawaban_siswa' => $request->jawaban_siswa,
            'status' => $status
        ]);
    } else {
        $jawaban = JawabanPengguna::create([
            'id_user' => $user->id_user,
            'id_soal' => $soal->id_soal,
            'jawaban_siswa' => $request->jawaban_siswa,
            'status' => $status
        ]);
    }

    // ✅ updateSkor dipindah ke luar if else
    if ($status === 'benar') {
        $this->updateSkor($user->id_user, $soal->id_level, $id_mataPelajaran, $tipeSoal);
    }

    $this->updateRekap($user->id_user, $soal->id_level, $id_mataPelajaran, $tipeSoal, $status);

    $rekap = RekapSkorPengguna::where('id_user', $user->id_user)
        ->where('id_mataPelajaran', $id_mataPelajaran)
        ->where('id_level', $soal->id_level)
        ->first();

    return response()->json([
        'message' => 'Jawaban disimpan dan rekap skor diperbarui',
        'jawaban' => $jawaban,
        'rekap' => $rekap
    ], 200);
}

 

    private function updateSkor($userId, $levelId, $mataPelajaranId, $tipeSoal)
{
    // Tambah entri baru setiap kali menjawab benar
    SkorPengguna::create([
        'id_user' => $userId,
        'id_mataPelajaran' => $mataPelajaranId,
        'id_level' => $levelId,
        'tipeSoal' => $tipeSoal,
        'jumlah_benar' => 1
    ]);
}

    
private function updateRekap($userId, $levelId, $mataPelajaranId, $tipeSoal, $status)
{
    $rekap = RekapSkorPengguna::firstOrCreate([
        'id_user' => $userId,
        'id_mataPelajaran' => $mataPelajaranId,
        'id_level' => $levelId
    ], [
        'total_visual' => 0,
        'total_auditori' => 0,
        'total_kinestetik' => 0
    ]);

    if ($status === 'benar') {
        if ($tipeSoal === 'visual') {
            $rekap->increment('total_visual');
        } elseif ($tipeSoal === 'auditori') {
            $rekap->increment('total_auditori');
        } elseif ($tipeSoal === 'kinestetik') {
            $rekap->increment('total_kinestetik');
        }
    }
}

    

    //cek kelulusan
    
    public function cekKelulusanLevel(Request $request)
{
    $request->validate([
        'id_user' => 'required|integer',
        'id_mataPelajaran' => 'required|integer',
        'id_level' => 'required|integer',
    ]);

    $jumlahBenar = SkorPengguna::where('id_user', $request->id_user)
        ->where('id_mataPelajaran', $request->id_mataPelajaran)
        ->where('id_level', $request->id_level)
        ->sum('jumlah_benar');

    if ($jumlahBenar >= 3) {
        return response()->json([
            'status' => 'success',
            'message' => 'Kamu bisa lanjut ke level berikutnya.',
            'boleh_lanjut' => true,
            'jumlah_benar' => $jumlahBenar
        ]);
    } else {
        return response()->json([
            'status' => 'failed',
            'message' => 'Minimal 3 soal benar untuk bisa lanjut ke level berikutnya.',
            'boleh_lanjut' => false,
            'jumlah_benar' => $jumlahBenar
        ]);
    }
}
 
public function getSkorAkhir()
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User tidak ditemukan.'
        ], 401);
    }

    // Ambil rekap skor dari semua mata pelajaran dan level
    $rekapList = RekapSkorPengguna::where('id_user', $user->id_user)->get();

    // Inisialisasi total
    $totalVisual = 0;
    $totalAuditori = 0;
    $totalKinestetik = 0;

    foreach ($rekapList as $rekap) {
        $totalVisual += $rekap->total_visual;
        $totalAuditori += $rekap->total_auditori;
        $totalKinestetik += $rekap->total_kinestetik;
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Total skor akhir berhasil diambil.',
        'data' => [
            'total_visual' => $totalVisual,
            'total_auditori' => $totalAuditori,
            'total_kinestetik' => $totalKinestetik
        ]
    ]);
}

public function getSkorAkhirPerLevel(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'User tidak ditemukan.'
        ], 401);
    }

    $request->validate([
        'id_mataPelajaran' => 'required|integer',
        'id_level' => 'required|integer',
    ]);

    $rekap = RekapSkorPengguna::where('id_user', $user->id_user)
        ->where('id_mataPelajaran', $request->id_mataPelajaran)
        ->where('id_level', $request->id_level)
        ->first();

    if (!$rekap) {
        return response()->json([
            'status' => 'error',
            'message' => 'Rekap skor tidak ditemukan untuk level dan mata pelajaran tersebut.',
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Skor akhir berhasil diambil.',
        'data' => [
            'total_visual' => $rekap->total_visual,
            'total_auditori' => $rekap->total_auditori,
            'total_kinestetik' => $rekap->total_kinestetik
        ]
    ]);
}



}