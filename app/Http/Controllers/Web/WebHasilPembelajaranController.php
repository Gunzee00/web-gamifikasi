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

    //statistik
    public function show($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $rekapSkor = RekapSkorPengguna::where('id_user', $id)->get();
 
        foreach ($rekapSkor as $rekap) {
            $tipe_dominan = 'Tidak Ada';
            $maxSkor = max($rekap->total_visual, $rekap->total_auditory, $rekap->total_kinestetik);
        
            if ($maxSkor > 0) {
                if ($rekap->total_visual == $maxSkor) {
                    $tipe_dominan = 'visual';
                }
                if ($rekap->total_auditory == $maxSkor) {
                    $tipe_dominan = 'auditory';
                }
                if ($rekap->total_kinestetik == $maxSkor) {
                    $tipe_dominan = 'kinestetik';
                }
            }
    
            $rekap->tipe_dominan = $tipe_dominan;
        }
    
        return view('admin.hasilpembelajaran.show', [
            'title' => 'Detail Hasil Pembelajaran',
            'user' => $user,
            'rekapSkor' => $rekapSkor,
        ]);
    }
    
    public function statistikHasilPembelajaran($userId)
    {
        $rekapSkor = RekapSkorPengguna::where('id_user', $userId)->get();
    
        foreach ($rekapSkor as $rekap) {
            $maxSkor = max($rekap->total_visual, $rekap->total_auditory, $rekap->total_kinestetik);
            //menentukan tipe dominan
            if ($maxSkor <= 0) {
                $rekap->tipe_dominan = 'Tidak Ada';
            } elseif ($rekap->total_visual == $maxSkor) {
                $rekap->tipe_dominan = 'visual';
            } elseif ($rekap->total_auditory == $maxSkor) {
                $rekap->tipe_dominan = 'auditory';
            } elseif ($rekap->total_kinestetik == $maxSkor) {
                $rekap->tipe_dominan = 'kinestetik';
            }
        }
    
        $user = User::find($userId);
    
        return view('statistik.index', compact('rekapSkor', 'user'));
    }
    

    

}
