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
        $this->middleware('auth:sanctum');
    }

    public function registerAdmin(Request $request)
    {
        // Memastikan yang bisa mendaftarkan admin hanya super_admin
        if (auth()->user()->role !== 'super_admin') {
            return response()->json([
                'message' => 'Anda tidak memiliki izin untuk mendaftarkan admin.'
            ], 403);
        }
 
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required|in:laki-laki,perempuan',
        ]);

 
        $admin = User::create([
            'role' => 'admin',  
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
