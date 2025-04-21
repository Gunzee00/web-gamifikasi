<?php
namespace App\Http\Controllers\Web;
use Illuminate\Validation\Rule;
        
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\MataPelajaran;

class WebLevelController extends Controller
{
    public function index(Request $request)
{
    $mataPelajaran = MataPelajaran::all();
    
    $query = Level::with('mataPelajaran');

    // Cek apakah ada filter
    if ($request->has('id_mataPelajaran') && $request->id_mataPelajaran != '') {
        $query->where('id_mataPelajaran', $request->id_mataPelajaran);
    }

    $levels = $query->get();

    return view('admin.level.level', [
        'title' => 'Web Level',
        'levels' => $levels,
        'mataPelajaran' => $mataPelajaran,
        'selectedMataPelajaran' => $request->id_mataPelajaran // untuk menandai tombol aktif
    ]);
}   

public function filter($id_mataPelajaran)
{
    $levels = Level::with('mataPelajaran')
        ->where('id_mataPelajaran', $id_mataPelajaran)
        ->get();

    return response()->json([
        'html' => view('admin.level.partial_level_table', compact('levels'))->render()
    ]);
}


public function store(Request $request)
{
    $request->validate([
        'penjelasan_level' => [
            'required',
            Rule::unique('level')->where(function ($query) use ($request) {
                return $query->where('id_mataPelajaran', $request->id_mataPelajaran);
            }),
        ],
        'id_mataPelajaran' => 'required',
    ], [
        'penjelasan_level.required' => 'Nama level wajib diisi.',
        'penjelasan_level.unique' => 'Level tersebut sudah ada untuk mata pelajaran yang dipilih.',
        'id_mataPelajaran.required' => 'Mata pelajaran wajib dipilih.',
    ]);

    Level::create([
        'id_mataPelajaran' => $request->id_mataPelajaran,
        'penjelasan_level' => $request->penjelasan_level,
    ]);

    return redirect()->route('admin.levels.index')->with('success', 'Level berhasil ditambahkan.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'id_mataPelajaran' => 'required|exists:matapelajaran,id_mataPelajaran',
        'penjelasan_level' => [
            'required',
            'string',
            'max:255',
            Rule::unique('level')
                ->where(function ($query) use ($request) {
                    return $query->where('id_mataPelajaran', $request->id_mataPelajaran);
                })
                ->ignore($id, 'id_level') // agar tidak konflik dengan dirinya sendiri saat edit
        ],
    ]);

    $level = Level::findOrFail($id);
    $level->update([
        'id_mataPelajaran' => $request->id_mataPelajaran,
        'penjelasan_level' => $request->penjelasan_level,
    ]);

    return redirect()->route('admin.levels.index')->with('success', 'Level berhasil diperbarui.');
}


    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        return redirect()->route('admin.levels.index')->with('success', 'Level berhasil dihapus.');
    }

    public function getLevelsByMataPelajaran($id_mataPelajaran)
    {
        $levels = Level::where('id_mataPelajaran', $id_mataPelajaran)
                       ->with('mataPelajaran')
                       ->get();
    
        return response()->json(['levels' => $levels]); // Bungkus dalam array levels
    }
    
}
