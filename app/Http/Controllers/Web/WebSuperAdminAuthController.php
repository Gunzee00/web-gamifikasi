<?php

namespace App\Http\Controllers\Web;  
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
class WebSuperAdminAuthController extends Controller
{

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

//     public function editUser($id)
// {
//     $user = User::findOrFail($id);

//     if (Auth::user()->role !== 'super_admin') {
//         abort(403, 'Akses ditolak.');
//     }

//     return view('super_admin.edit_user', [
//         'title' => 'Edit Akun',
//         'user' => $user
//     ]);
// }


// public function updateUser(Request $request, $id)
// {
//     $request->validate([
//         'name' => 'required|string|max:255',
//         'username' => 'required|string|unique:users,username,' . $id,
//         'gender' => 'required|in:laki-laki,perempuan',
//         'tanggal_lahir' => 'required|date',
//         'role' => 'required|in:admin,user',
//     ]);

//     $user = User::findOrFail($id);

//     if (Auth::user()->role !== 'super_admin') {
//         abort(403, 'Akses ditolak.');
//     }

//     $user->name = $request->name;
//     $user->username = $request->username;
//     $user->gender = $request->gender;
//     $user->tanggal_lahir = $request->tanggal_lahir;
//     $user->role = $request->role;

//     if ($request->filled('password')) {
//         $request->validate([
//             'password' => 'required|string|min:6|confirmed',
//         ]);
//         $user->password = Hash::make($request->password);
//     }

//     $user->save();

//     return redirect()->route('super_admin.list_users')->with('success', 'Data akun berhasil diperbarui.');
// }


public function deleteUser($id)
{
    $user = User::findOrFail($id);

    if (Auth::user()->role !== 'super_admin') {
        abort(403, 'Akses ditolak.');
    }

    if ($user->role === 'super_admin') {
        return redirect()->back()->with('error', 'Tidak bisa menghapus super admin.');
    }

    $user->delete();

    return redirect()->route('super_admin.list_users')->with('success', 'Akun berhasil dihapus.');
}

public function manajemenAkun()
{
    $users = User::where('role', '!=', 'super_admin')->get(); // Ambil semua akun kecuali super_admin
    return view('super_admin.manajemen_akun', [
        'users' => $users,
        'title' => 'Manajemen Akun'
    ]);
}
public function editUser($id_user)
{
    if (Auth::user()->role !== 'super_admin') {
        abort(403, 'Akses ditolak.');
    }

    $user = User::where('id_user', $id_user)->firstOrFail();
    return view('super_admin.edit_akun', [
        'title' => 'Edit Akun Pengguna',
        'user' => $user
    ]);
}


public function updateUser(Request $request, $id_user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|unique:users,username,' . $id_user . ',id_user',
        'gender' => 'required|in:laki-laki,perempuan',
        'tanggal_lahir' => 'required|date',
        'role' => 'required|in:admin,user',
    ]);

    $user = User::where('id_user', $id_user)->firstOrFail();

    if (Auth::user()->role !== 'super_admin') {
        abort(403, 'Akses ditolak.');
    }

    $user->name = $request->name;
    $user->username = $request->username;
    $user->gender = $request->gender;
    $user->tanggal_lahir = $request->tanggal_lahir;
    $user->role = $request->role;

    if ($request->filled('password')) {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('super_admin.list_users')->with('success', 'Data akun berhasil diperbarui.');
}

public function listUsers()
{
    // Ambil semua user selain super_admin
    $users = User::where('role', '!=', 'super_admin')->get();

    // Tambahkan variabel $title
    $title = 'Manajemen Akun';

    // Tampilkan view dengan data users dan title
    return view('super_admin.manajemen_akun', compact('users', 'title'));
}






    

    
}
