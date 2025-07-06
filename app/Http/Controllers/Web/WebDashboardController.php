<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Matapelajaran;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Level;
use App\Models\Topik;
use App\Models\Soal;

class WebDashboardController extends Controller
{
    public function index()
    {
        $totalLevel = Level::count();
        $totalTopik = Topik::count();
        $totalSoal = Soal::count();

        return view('main.home', [
            'title' => 'Dashboard',
            'totalLevel' => $totalLevel,
            'totalTopik' => $totalTopik,
            'totalSoal' => $totalSoal,
        ]);
    }
}
