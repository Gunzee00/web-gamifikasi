<?php

namespace App\Http\Controllers\Web;  
use App\Http\Controllers\Controller;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

use App\Models\Topik;
use App\Models\Level;
use Illuminate\Http\Request;

class WebTopikController extends Controller
{
   public function index()
{
    $topiks = Topik::with('level')->get();
    $levels = Level::all();
    $title = 'Manajemen Topik'; // <-- tambahkan ini

    return view('admin.topik.topik', compact('topiks', 'levels', 'title'));
}
 

public function store(Request $request)
{
    // Step 1: Validasi input
    $request->validate([
        'id_level' => 'required|exists:level,id_level',
        'nama_topik' => 'required|string|max:255',
        'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
    ]);

    // Step 2: Debug apakah file terkirim
    if (!$request->hasFile('icon')) {
        dd('File icon tidak terkirim');
    }

    // Step 3: Upload pakai Cloudinary seperti soal
    $uploadOrText = function ($name, $folder) use ($request) {
        if ($request->hasFile($name)) {
            return Cloudinary::upload(
                $request->file($name)->getRealPath(),
                ['folder' => $folder, 'resource_type' => 'auto']
            )->getSecurePath();
        }
        return $request->input($name);
    };

    // Step 4: Proses upload icon
    $iconUrl = $uploadOrText('icon', 'topik/icon');

    // Step 5: Debug hasil URL
    if (!$iconUrl) {
        dd('Upload ke Cloudinary gagal atau URL kosong');
    }

    // Step 6: Simpan ke DB
    $topik = Topik::create([
        'id_level' => $request->id_level,
        'nama_topik' => $request->nama_topik,
        'icon' => $iconUrl,
    ]);

    // Step 7: Debug data yang dikirim ke DB
    // dd($topik);

    return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil ditambahkan.');
}



  public function update(Request $request, $id)
{
    $topik = Topik::findOrFail($id);

    $request->validate([
        'id_level' => 'required|exists:level,id_level',
        'nama_topik' => 'required|string|max:255',
        'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
    ]);

    $data = [
        'id_level' => $request->id_level,
        'nama_topik' => $request->nama_topik,
    ];

    if ($request->hasFile('icon')) {
        // upload ke Cloudinary
        $iconUrl = Cloudinary::upload(
            $request->file('icon')->getRealPath(),
            ['folder' => 'topik/icon', 'resource_type' => 'image']
        )->getSecurePath();

        $data['icon'] = $iconUrl;

        // Optional: hapus file lama jika kamu menyimpan public_id-nya
    }

    $topik->update($data);

    return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil diperbarui.');
}


    public function destroy($id)
    {
        $topik = Topik::findOrFail($id);
        $topik->delete();
        return redirect()->route('admin.topik.index')->with('success', 'Topik berhasil dihapus.');
    }


    public function showByLevel($id_level)
{
    $level = Level::findOrFail($id_level);
    $topiks = Topik::where('id_level', $id_level)->get();
    $title =   $level->nama_level;

    return view('admin.soal.topik', compact('topiks', 'level', 'title'));
}

public function createByTopik($id_topik)
{
    $topik = \App\Models\Topik::with('level')->findOrFail($id_topik);

    return view('admin.soal.create', [
        'title' => 'Tambah Soal untuk Topik ' . $topik->nama_topik,
        'topik' => $topik,
        'level' => $topik->level, // opsional kalau masih mau pakai id_level
    ]);
}


}