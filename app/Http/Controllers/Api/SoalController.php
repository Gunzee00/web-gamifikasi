<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; // Pastikan ini sudah benar
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SoalController extends Controller
{
    /**
     * Menampilkan semua data soal.
     */
  // Menampilkan semua soal
  public function index()
  {
      $soal = Soal::all();
      return response()->json([
          'success' => true,
          'message' => 'List semua soal',
          'data' => $soal
      ], 200);
  }

  public function getByMataPelajaranAndLevel($id_mataPelajaran, $id_level)
  {
      $level = Level::where('id_mataPelajaran', $id_mataPelajaran)
                    ->where('id_level', $id_level)
                    ->first();

      if (!$level) {
          return response()->json([
              'status' => 'error',
              'message' => 'Level tidak ditemukan untuk mata pelajaran ini'
          ], 404);
      }

      $soal = Soal::where('id_level', $id_level)->get();

      if ($soal->isEmpty()) {
          return response()->json([
              'status' => 'error',
              'message' => 'Tidak ada soal untuk level ini'
          ], 404);
      }

      return response()->json([
          'status' => 'success',
          'mataPelajaran' => $level->mataPelajaran->nama_mataPelajaran,
          'level' => $level->penjelasan_level,
          'soal' => $soal
      ], 200);
  }

  public function getByLevel($id_level)
{
    // Cek apakah level ada
    $level = Level::where('id_level', $id_level)->first();
    
    if (!$level) {
        return response()->json([
            'status' => 'error',
            'message' => 'Level tidak ditemukan'
        ], 404);
    }

    // Ambil soal berdasarkan id_level
    $soal = Soal::where('id_level', $id_level)->get();

    if ($soal->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Tidak ada soal untuk level ini'
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'level' => $level->penjelasan_level,
        'soal' => $soal
    ], 200);
}

  //

    /**
     * Menyimpan soal baru dengan media dan audio ke Cloudinary.
     */
  
    public function store(Request $request)
{
    // Validasi input dasar
    $request->validate([
        'id_level' => 'required|exists:level,id_level',
        'tipeSoal' => 'required|in:visual1,visual2,auditori1,auditori2,kinestetik1,kinestetik2',
        'pertanyaan' => 'required|string',
        'jawabanBenar' => 'nullable|string'
    ]);

    // Fungsi reusable: jika file, upload ke Cloudinary. Jika teks, ambil langsung.
    $uploadOrText = function ($name, $folder) use ($request) {
        if ($request->hasFile($name)) {
            return Cloudinary::upload($request->file($name)->getRealPath(), [
                'folder' => $folder,
                'resource_type' => 'auto'
            ])->getSecurePath();
        } else {
            return $request->input($name); // Ambil teks jika bukan file
        }
    };

    // Proses semua kolom yang bisa teks/file
    $audioPertanyaan = $uploadOrText('audioPertanyaan', 'soal/audio');
    $media           = $uploadOrText('media', 'soal/media');

    $opsiA = $uploadOrText('opsiA', 'soal/opsi');
    $opsiB = $uploadOrText('opsiB', 'soal/opsi');
    $opsiC = $uploadOrText('opsiC', 'soal/opsi');
    $opsiD = $uploadOrText('opsiD', 'soal/opsi');

    $pasanganA = $uploadOrText('pasanganA', 'soal/pasangan');
    $pasanganB = $uploadOrText('pasanganB', 'soal/pasangan');
    $pasanganC = $uploadOrText('pasanganC', 'soal/pasangan');
    $pasanganD = $uploadOrText('pasanganD', 'soal/pasangan');

    // Simpan data ke database
    $soal = Soal::create([
        'id_level' => $request->id_level,
        'tipeSoal' => $request->tipeSoal,
        'pertanyaan' => $request->pertanyaan,
        'audioPertanyaan' => $audioPertanyaan,
        'media' => $media,
        'opsiA' => $opsiA,
        'opsiB' => $opsiB,
        'opsiC' => $opsiC,
        'opsiD' => $opsiD,
        'pasanganA' => $pasanganA,
        'pasanganB' => $pasanganB,
        'pasanganC' => $pasanganC,
        'pasanganD' => $pasanganD,
        'jawabanBenar' => $request->jawabanBenar,
    ]);

    return response()->json([
        'message' => 'Soal berhasil ditambahkan!',
        'data' => $soal
    ], 201);
}
public function show($id)
{
    $soal = Soal::find($id);

    if (!$soal) {
        return response()->json([
            'success' => false,
            'message' => 'Soal tidak ditemukan',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'message' => 'Detail soal',
        'data' => $soal
    ], 200);
}

    
     

}