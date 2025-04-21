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
        return view('auth.login'); // Menampilkan view login.blade.php di dalam folder auth
    }
    
    // Show Home Page after login
    public function home()
    {
        return view('main.home'); // Returning the landing page after login (login/home.blade.php)
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
        // Jika role bukan admin atau super_admin, logout dan tampilkan pesan error
        Auth::logout();
        throw ValidationException::withMessages([
            'username' => ['Username dan Password salah'],
        ]);
    }

    // Log in the user jika role sesuai
    Auth::login($user);

    // Redirect ke halaman home setelah login berhasil
    return redirect()->route('home');
}


   
    // Logout User
public function logout(Request $request)
{
    // Logout user menggunakan guard 'web'
    Auth::guard('web')->logout();

    // Invalidate session dan regenerasi token untuk mencegah session fixation
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirect ke halaman login dengan pesan sukses (opsional)
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
            'password' => 'required|string|min:6|confirmed', // Pastikan ada konfirmasi password
            'role' => 'required|in:admin', // Role hanya bisa admin
            'name' => 'required|string|max:255',
            'gender' => 'required|in:laki-laki,perempuan',
'tanggal_lahir' => 'required|date', // Menambahkan validasi tanggal lahir
        ]);
        
        // Hanya super admin yang dapat mendaftarkan admin
        if (Auth::user()->role !== 'super_admin') {
            throw ValidationException::withMessages([
                'username' => ['Anda tidak memiliki izin untuk melakukan ini.'],
            ]);
        }
    
        try {
            // Membuat user baru dengan role admin
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password = Hash::make($request->password); // Password di-hash
            $user->role = 'admin'; // Memberikan role admin
            $user->gender = $request->gender; // Menyimpan gender
            $user->tanggal_lahir = $request->tanggal_lahir; // Menyimpan tanggal lahir
            $user->save();
        
            return redirect()->route('main.home')->with('success', 'Admin baru telah didaftarkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
    

    
}
