<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Soal;
use App\Models\Topik;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class SoalController extends Controller
{
    // Menampilkan semua soal
    public function index()
    {
        $soal = Soal::with('topik')->get();

        return response()->json([
            'success' => true,
            'message' => 'List semua soal',
            'data' => $soal
        ]);
    }

    // Menampilkan soal berdasarkan topik
    public function getByTopik($id_topik)
    {
        $topik = Topik::find($id_topik);

        if (!$topik) {
            return response()->json([
                'success' => false,
                'message' => 'Topik tidak ditemukan'
            ], 404);
        }

        $soal = Soal::where('id_topik', $id_topik)->get();

        return response()->json([
            'success' => true,
            'topik' => $topik->nama_topik,
            'data' => $soal
        ]);
    }

    // Menambahkan soal baru
    public function store(Request $request)
    {
        $request->validate([
            'id_topik' => 'required|exists:topik,id_topik',
            'tipeSoal' => 'required|in:visual1,visual2,auditori1,auditori2,kinestetik1,kinestetik2',
            'pertanyaan' => 'required|string',
            'jawabanBenar' => 'nullable|string',
        ]);

        // Fungsi upload dari file atau teks
        $uploadOrText = function ($name, $folder) use ($request) {
            if ($request->hasFile($name)) {
                return Cloudinary::upload($request->file($name)->getRealPath(), [
                    'folder' => $folder,
                    'resource_type' => 'auto'
                ])->getSecurePath();
            } else {
                return $request->input($name);
            }
        };

        $soal = Soal::create([
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
            'jawabanBenar' => $request->jawabanBenar,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil ditambahkan',
            'data' => $soal
        ], 201);
    }

    // Menampilkan detail soal
    public function show($id)
    {
        $soal = Soal::with('topik')->find($id);

        if (!$soal) {
            return response()->json([
                'success' => false,
                'message' => 'Soal tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $soal
        ]);
    }

    // Update soal
    public function update(Request $request, $id)
    {
        $soal = Soal::find($id);

        if (!$soal) {
            return response()->json([
                'success' => false,
                'message' => 'Soal tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'id_topik' => 'sometimes|required|exists:topik,id_topik',
            'tipeSoal' => 'sometimes|required|in:visual1,visual2,auditori1,auditori2,kinestetik1,kinestetik2',
            'pertanyaan' => 'sometimes|required|string',
        ]);

        $uploadOrText = function ($name, $folder, $oldValue) use ($request) {
            if ($request->hasFile($name)) {
                return Cloudinary::upload($request->file($name)->getRealPath(), [
                    'folder' => $folder,
                    'resource_type' => 'auto'
                ])->getSecurePath();
            } else {
                return $request->input($name, $oldValue);
            }
        };

        $soal->update([
            'id_topik' => $request->input('id_topik', $soal->id_topik),
            'tipeSoal' => $request->input('tipeSoal', $soal->tipeSoal),
            'pertanyaan' => $request->input('pertanyaan', $soal->pertanyaan),
            'audioPertanyaan' => $uploadOrText('audioPertanyaan', 'soal/audio', $soal->audioPertanyaan),
            'media' => $uploadOrText('media', 'soal/media', $soal->media),
            'opsiA' => $uploadOrText('opsiA', 'soal/opsi', $soal->opsiA),
            'opsiB' => $uploadOrText('opsiB', 'soal/opsi', $soal->opsiB),
            'opsiC' => $uploadOrText('opsiC', 'soal/opsi', $soal->opsiC),
            'opsiD' => $uploadOrText('opsiD', 'soal/opsi', $soal->opsiD),
            'pasanganA' => $uploadOrText('pasanganA', 'soal/pasangan', $soal->pasanganA),
            'pasanganB' => $uploadOrText('pasanganB', 'soal/pasangan', $soal->pasanganB),
            'pasanganC' => $uploadOrText('pasanganC', 'soal/pasangan', $soal->pasanganC),
            'pasanganD' => $uploadOrText('pasanganD', 'soal/pasangan', $soal->pasanganD),
            'jawabanBenar' => $request->input('jawabanBenar', $soal->jawabanBenar),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil diperbarui',
            'data' => $soal
        ]);
    }

    // Hapus soal
    public function destroy($id)
    {
        $soal = Soal::find($id);

        if (!$soal) {
            return response()->json([
                'success' => false,
                'message' => 'Soal tidak ditemukan'
            ], 404);
        }

        $soal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil dihapus'
        ]);
    }

    
}
