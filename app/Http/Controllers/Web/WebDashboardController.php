<?php
namespace App\Http\Controllers\Web;  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Matapelajaran;  
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class WebDashboardController extends Controller
{
    public function index()
    {
        // Menghitung total user
        $totalUsers = User::count(); 
        // Menghitung jumlah mata pelajaran
        $totalMatapelajaran = Matapelajaran::count(); 

        return view('main.home', [
            'title' => 'Dashboard',
            'totalUsers' => $totalUsers,  
            'totalMatapelajaran' => $totalMatapelajaran, 
        ]);
    }
}