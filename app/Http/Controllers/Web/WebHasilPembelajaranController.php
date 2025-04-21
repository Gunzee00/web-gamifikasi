<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\RekapSkorPengguna;
use App\Models\User;

class WebHasilPembelajaranController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')->get();

        return view('admin.hasilpembelajaran.index', [
            'title' => 'Hasil Pembelajaran',
            'users' => $users
        ]);
    }

    public function show($id)
{
    $user = User::where('role', 'user')->findOrFail($id);
    $rekapSkor = RekapSkorPengguna::where('id_user', $id)->get();

    return view('admin.hasilpembelajaran.show', [
        'title' => 'Detail Hasil Pembelajaran',
        'user' => $user,
        'rekapSkor' => $rekapSkor
    ]);
}

}
