<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register User
    public function register(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,super_admin,user',
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
            'gender' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date_format:Y-m-d', 
        ]);
    
        $user = User::create([
            'role' => $request->role,
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'tanggal_lahir' => $request->tanggal_lahir, // Simpan tanggal lahir
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'user' => [
                'id_user' => $user->id_user,
                'role' => $user->role,
                'name' => $user->name,
                'username' => $user->username,
                'gender' => $user->gender,
                'tanggal_lahir' => $user->tanggal_lahir, // Kirim tanggal lahir dalam response
            ]
        ], 201);
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id_user' => $user->id_user,
                'role' => $user->role,
                'name' => $user->name,
                'username' => $user->username,
                'gender' => $user->gender,
                'tanggal_lahir' => $user->tanggal_lahir, // Kirim tanggal lahir saat login
            ]
        ]);
    }

    // Get Authenticated User Data
    public function me(Request $request)
    {
        return response()->json([
            'id_user' => $request->user()->id_user,
            'role' => $request->user()->role,
            'name' => $request->user()->name,
            'username' => $request->user()->username,
            'gender' => $request->user()->gender,
            'tanggal_lahir' => $request->user()->tanggal_lahir, // Kirim tanggal lahir dalam data user
        ]);
    }

    public function lupaPassword(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'tanggal_lahir' => 'required|date_format:Y-m-d',
        'password_baru' => 'required|string|min:6|confirmed', // gunakan password_baru + password_baru_confirmation
    ]);

    $user = User::where('username', $request->username)
                ->where('tanggal_lahir', $request->tanggal_lahir)
                ->where('role', 'user') // hanya untuk role user
                ->first();

    if (!$user) {
        return response()->json([
            'message' => 'Data tidak ditemukan atau tidak cocok.'
        ], 404);
    }

    $user->password = Hash::make($request->password_baru);
    $user->save();

    return response()->json([
        'message' => 'Password berhasil diubah.',
    ]);
}
}
