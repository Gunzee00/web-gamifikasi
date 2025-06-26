<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\Topik;
use App\Models\Level;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class WebSoalController extends Controller
{
    public function index()
    {
        $levels = Level::all();
        return view('admin.soal.index', [
            'title' => 'Soal',
            'levels' => $levels
        ]);
    }

   public function store(Request $request)
{
    // Validasi dasar
    $request->validate([
        'id_topik' => 'required|exists:topik,id_topik',
        'tipeSoal' => 'required|in:visual1,visual2,auditori1,auditori2,kinestetik1,kinestetik2',
        'pertanyaan' => 'required|string',
    ]);

    // Fungsi reusable upload/take text
    $uploadOrText = function ($name, $folder) use ($request) {
        if ($request->hasFile($name)) {
            return Cloudinary::upload(
                $request->file($name)->getRealPath(),
                ['folder' => $folder, 'resource_type' => 'auto']
            )->getSecurePath();
        }
        return $request->input($name); // bisa teks
    };

    // Upload media
    $audioPertanyaan = $uploadOrText('audioPertanyaan', 'soal/audio');
    $media = $uploadOrText('media', 'soal/media');

    // Opsi dan pasangan
    $opsiA = $uploadOrText('opsiA', 'soal/opsi');
    $opsiB = $uploadOrText('opsiB', 'soal/opsi');
    $opsiC = $uploadOrText('opsiC', 'soal/opsi');
    $opsiD = $uploadOrText('opsiD', 'soal/opsi');

    $pasanganA = $uploadOrText('pasanganA', 'soal/pasangan');
    $pasanganB = $uploadOrText('pasanganB', 'soal/pasangan');
    $pasanganC = $uploadOrText('pasanganC', 'soal/pasangan');
    $pasanganD = $uploadOrText('pasanganD', 'soal/pasangan');

    // Tangani jawaban benar sesuai tipe
    if ($request->tipeSoal === 'kinestetik1') {
        $jawabanBenar = json_encode($request->input('jawaban_pair', []));
    } elseif ($request->tipeSoal === 'kinestetik2') {
        $jawabanBenar = $request->input('jawabanBenarText');
    } else {
        $jawabanBenar = $request->input('jawabanBenar');
    }

    // Simpan soal ke database
    $soal = Soal::create([
        'id_topik' => $request->id_topik,
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
        'jawabanBenar' => $jawabanBenar,
    ]);

    // ✅ Redirect ke daftar soal berdasarkan topik
    return redirect()->route('admin.topik.show_soal', ['id' => $request->id_topik])
        ->with('success', 'Soal berhasil disimpan!');
}


    public function createByTopik($id_topik)
    {
        $topik = Topik::with('level')->findOrFail($id_topik);

        return view('admin.soal.create', [
            'title' => 'Tambah Soal untuk Topik ' . $topik->nama_topik,
            'topik' => $topik,
            'level' => $topik->level // optional
        ]);
    }

    public function showSoalByTopik($id)
{
    $topik = Topik::with('level')->findOrFail($id);
    $soals = Soal::where('id_topik', $id)->paginate(5);

    return view('admin.soal.list_soal', [
        'title' => 'Soal untuk Topik: ' . $topik->nama_topik,
        'topik' => $topik,
        'soals' => $soals,
        'level' => $topik->level // ✅ Tambahkan baris ini
    ]);
}


    public function update(Request $request, $id)
    {
        $soal = Soal::findOrFail($id);

        $request->validate([
            'id_topik' => 'required|exists:topik,id_topik',
            'tipeSoal' => 'required|in:visual1,visual2,auditori1,auditori2,kinestetik1,kinestetik2',
            'pertanyaan' => 'required|string',
        ]);

        $uploadOrText = function ($name, $folder) use ($request, $soal) {
            if ($request->hasFile($name)) {
                return Cloudinary::upload(
                    $request->file($name)->getRealPath(),
                    ['folder' => $folder, 'resource_type' => 'auto']
                )->getSecurePath();
            }
            return $request->input($name, $soal->$name);
        };

        $soal->update([
            'id_topik' => $request->id_topik,
            'tipeSoal' => $request->tipeSoal,
            'pertanyaan' => $request->pertanyaan,
            'audioPertanyaan' => $uploadOrText('audioPertanyaan', 'soal/audio'),
            'media' => $uploadOrText('media', 'soal/media'),
            'opsiA' => $uploadOrText('opsiA', 'soal/opsi'),
            'opsiB' => $uploadOrText('opsiB', 'soal/opsi'),
            'opsiC' => $uploadOrText('opsiC', 'soal/opsi'),
            'opsiD' => $uploadOrText('opsiD', 'soal/opsi'),
            'pasanganA' => $uploadOrText('pasanganA', 'soal/pasangan'),
            'pasanganB' => $uploadOrText('pasanganB', 'soal/pasangan'),
            'pasanganC' => $uploadOrText('pasanganC', 'soal/pasangan'),
            'pasanganD' => $uploadOrText('pasanganD', 'soal/pasangan'),
            'jawabanBenar' => $request->tipeSoal === 'kinestetik1'
                ? json_encode($request->input('jawaban_pair', []))
                : ($request->tipeSoal === 'kinestetik2'
                    ? $request->input('jawabanBenarText')
                    : $request->input('jawabanBenar')),
        ]);

        return redirect()->route('admin.topik.show_soal', ['id' => $soal->id_topik])
            ->with('success', 'Soal berhasil diperbarui!');
    }

    public function edit($id)
    {
        $soal = Soal::findOrFail($id);
        $topik = $soal->topik; // pastikan relasi topik ada

        return view('admin.soal.edit_soal', [
            'title' => 'Edit Soal',
            'soal' => $soal,
            'topik' => $topik,
        ]);
    }

    public function destroy($id)
    {
        $soal = Soal::findOrFail($id);
        $topik_id = $soal->id_topik;
        $soal->delete();

        return redirect()->route('admin.topik.show_soal', ['id' => $topik_id])
            ->with('success', 'Soal berhasil dihapus!');
    }

    // Optional lama - jika masih ada fitur berdasarkan level
    public function showLevels($id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $levels = Level::where('id_mataPelajaran', $id)->get();

        return view('admin.soal.levels', [
            'title' => 'Pilih Level - ' . $mataPelajaran->nama_mataPelajaran,
            'mataPelajaran' => $mataPelajaran,
            'levels' => $levels
        ]);
    }
}
