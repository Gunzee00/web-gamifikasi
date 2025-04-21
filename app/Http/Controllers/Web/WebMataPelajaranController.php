<?php

namespace App\Http\Controllers\Web;  
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use Illuminate\Validation\Rule;

class WebMataPelajaranController extends Controller
{
    /**
     * Menampilkan halaman utama dengan data subjek.
     */
    public function index()
    {
        $matapelajaran = MataPelajaran::all();  
        return view('admin.matapelajaran.index', [
            'title' => 'Mata Pelajaran',
            'matapelajaran' => $matapelajaran, // Kirim data ke view
        ]);
    }
    
    /**
     * Menyimpan subjek baru.
     */
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_mataPelajaran' => 'required|string|max:255|unique:matapelajaran,nama_mataPelajaran',
        ], [
            'nama_mataPelajaran.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mataPelajaran.unique' => 'Nama mata pelajaran sudah ada. Silakan masukkan nama yang berbeda.',
        ]);

        MataPelajaran::create($request->all());

        return redirect()->back()->with('success', 'Mata Pelajaran berhasil ditambahkan.');
    }

    /**
     * Memperbarui mata pelajaran.
     */
    public function update(Request $request, $id)
    {
       $matapelajaran = MataPelajaran::findOrFail($id);

        $request->validate([
            'nama_mataPelajaran' => [
                'required',
                'string',
                'max:255',
                Rule::unique('matapelajaran', 'nama_mataPelajaran')->ignore($matapelajaran->id_mataPelajaran, 'id_mataPelajaran'),
            ],
        ], [
            'nama_mataPelajaran.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mataPelajaran.unique' => 'Nama mata pelajaran sudah ada. Silakan masukkan nama yang berbeda.',
        ]);

       $matapelajaran->update($request->all());

        return redirect()->back()->with('success', 'Nama mata pelajaran berhasil diperbarui.');
    }

    /**
     * Menghapus Mata Pelajaran.
     */
    public function destroy($id)
    {
       $matapelajaran = MataPelajaran::findOrFail($id);
       $matapelajaran->delete();

        return redirect()->back()->with('success', 'Nama mata pelajaran berhasil dihapus.');
    }
}
