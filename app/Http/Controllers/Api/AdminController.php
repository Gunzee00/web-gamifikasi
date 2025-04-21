<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // Memastikan hanya user yang login bisa mengakses
    }

    public function registerAdmin(Request $request)
    {
        // Pastikan yang bisa mendaftarkan admin hanya super_admin
        if (auth()->user()->role !== 'super_admin') {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mendaftarkan admin.'
            ], 403);
        }

        // Validasi data input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required|in:laki-laki,perempuan',
        ]);

        // Buat akun admin
        $admin = User::create([
            'role' => 'admin', // Role di-set otomatis sebagai admin
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
        ]);

        return response()->json([
            'message' => 'Admin berhasil didaftarkan!',
            'user' => [
                'id_user' => $admin->id_user,
                'role' => $admin->role,
                'name' => $admin->name,
                'username' => $admin->username,
                'gender' => $admin->gender,
            ]
        ], 201);
    }
}
