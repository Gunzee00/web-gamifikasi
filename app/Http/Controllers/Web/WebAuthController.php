<?php
namespace App\Http\Controllers\Web;  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{

    //login
    public function showLoginForm()
    {
        return view('auth.login');  
    }
    
    // dashboard setelah login
    public function home()
    {
        return view('main.home');  
    }

    // Login User
    public function login(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('username', $request->username)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'username' => ['Username atau password salah.'],
        ]);
    }

    // Cek apakah user memiliki role yang sesuai
    if (!in_array($user->role, ['admin', 'super_admin'])) {
        
        Auth::logout();
        throw ValidationException::withMessages([
            'username' => ['Username dan Password salah'],
        ]);
    }

    Auth::login($user);

    return redirect()->route('main.home');
}


   
    // Logout User
public function logout(Request $request)
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
}


    public function showRegistrationForm()
    {
        return view('super_admin.registration_admin', [
            'title' => 'Web Level',
        ]);
    }
    // Fungsi untuk menangani pendaftaran admin
    public function registerAdmin(Request $request)
    {
        // Validasi data input
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6|confirmed', 
            'role' => 'required|in:admin', 
            'name' => 'required|string|max:255',
            'gender' => 'required|in:laki-laki,perempuan',
       'tanggal_lahir' => 'required|date', 
        ]);
        
        if (Auth::user()->role !== 'super_admin') {
            throw ValidationException::withMessages([
                'username' => ['Anda tidak memiliki izin untuk melakukan ini.'],
            ]);
        }
    
        try {
          
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->role = 'admin'; 
            $user->gender = $request->gender;
            $user->tanggal_lahir = $request->tanggal_lahir; 
            $user->save();
        
            return redirect()->route('main.home')->with('success', 'Admin baru telah didaftarkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
    

    
}
