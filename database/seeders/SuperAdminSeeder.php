<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah super admin sudah ada
        if (!User::where('role', 'super_admin')->exists()) {
            User::create([
                'role' => 'super_admin',
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('password123'), // Ganti dengan password yang kuat
                'gender' => 'laki-laki'
            ]);
        }
    }
}
