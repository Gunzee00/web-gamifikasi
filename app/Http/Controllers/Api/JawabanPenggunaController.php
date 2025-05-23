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

        // Mengambil status jawaban sebelumnya (null jika belum ada jawaban)
        $jawabanSebelumnya = $jawaban ? $jawaban->status : null;

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

        // Update rekap hanya jika sekarang benar dan sebelumnya belum benar
        if ($status === 'benar' && $jawabanSebelumnya !== 'benar') {
            $this->updateRekap($user->id_user, $soal->id_level, $id_mataPelajaran, $tipeSoal);
        }

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

    private function updateRekap($userId, $levelId, $mataPelajaranId, $tipeSoal)
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

        // Ambil semua jawaban BENAR di level ini oleh user
        $jawabanBenar = JawabanPengguna::where('id_user', $userId)
            ->whereIn('id_soal', function ($query) use ($levelId) {
                $query->select('id_soal')->from('soal')->where('id_level', $levelId);
            })
            ->where('status', 'benar')
            ->get();

        // Hitung ulang jumlah benar per tipe soal
        $totalVisualBaru = 0;
        $totalAuditoriBaru = 0;
        $totalKinestetikBaru = 0;

        foreach ($jawabanBenar as $jawaban) {
            $soal = Soal::find($jawaban->id_soal);
            if (!$soal) continue;

            $tipe = strtolower($soal->tipeSoal);
            if (str_contains($tipe, 'visual')) {
                $totalVisualBaru++;
            } elseif (str_contains($tipe, 'auditori')) {
                $totalAuditoriBaru++;
            } elseif (str_contains($tipe, 'kinestetik')) {
                $totalKinestetikBaru++;
            }
        }

        // Hitung total skor lama dan skor baru
        $rekap->update([
            'total_visual' => $totalVisualBaru,
            'total_auditori' => $totalAuditoriBaru,
            'total_kinestetik' => $totalKinestetikBaru,
        ]);
    }

    //cek kelulusan
    
    public function cekKelulusanLevel(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'id_mataPelajaran' => 'required|integer',
            'id_level' => 'required|integer',
        ]);
    
        // Cari semua soal di level ini
        $soalIds = Soal::where('id_level', $request->id_level)->pluck('id_soal');
    
        // Hitung jumlah jawaban benar user di soal-soal tersebut
        $jumlahBenar = JawabanPengguna::where('id_user', $request->id_user)
            ->whereIn('id_soal', $soalIds)
            ->where('status', 'benar')
            ->count();
    
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
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Ambil soal terakhir yang dijawab user
    $jawabanTerakhir = JawabanPengguna::where('id_user', $user->id_user)
        ->orderByDesc('created_at')
        ->first();

    if (!$jawabanTerakhir) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Belum ada jawaban pengguna ditemukan.'
        ], 404);
    }

    $soal = Soal::find($jawabanTerakhir->id_soal);

    if (!$soal || !$soal->id_level) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Level dari soal tidak ditemukan.'
        ], 404);
    }

    // Hitung semua jawaban benar pada level tersebut
    $jumlahBenar = JawabanPengguna::where('id_user', $user->id_user)
        ->whereIn('id_soal', function ($query) use ($soal) {
            $query->select('id_soal')
                ->from('soal')
                ->where('id_level', $soal->id_level);
        })
        ->where('status', 'benar')
        ->count();

    return response()->json([
        'status' => 'success',
        'message' => 'Jumlah jawaban benar dari level terakhir berhasil dihitung.',
        'data' => [
            'id_level' => $soal->id_level,
            'id_mataPelajaran' => $soal->level->id_mataPelajaran ?? null,
            'jumlah_benar' => $jumlahBenar
        ]
    ]);
}

public function getSkorTerbaru(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Cari jawaban benar terakhir
    $jawabanTerakhir = JawabanPengguna::where('id_user', $user->id_user)
        ->orderByDesc('created_at') // pastikan pakai kolom waktu yang valid
        ->first();

    if (!$jawabanTerakhir) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Belum ada jawaban pengguna ditemukan.'
        ], 404);
    }

    $soal = Soal::find($jawabanTerakhir->id_soal);

    if (!$soal || !$soal->level) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Level dari soal tidak ditemukan.'
        ], 404);
    }

    // Hitung ulang jumlah benar dari level itu
    $jumlahBenar = JawabanPengguna::where('id_user', $user->id_user)
        ->whereIn('id_soal', function ($query) use ($soal) {
            $query->select('id_soal')
                ->from('soal')
                ->where('id_level', $soal->id_level);
        })
        ->where('status', 'benar')
        ->count();

    return response()->json([
        'status' => 'success',
        'message' => 'Skor dari level terakhir yang dikerjakan berhasil diambil.',
        'data' => [
            'id_level' => $soal->id_level,
            'id_mataPelajaran' => $soal->level->id_mataPelajaran,
            'jumlah_benar' => $jumlahBenar
        ]
    ]);
}

}