<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        $doctorRole = DB::table('roles')->where('name', 'doctor')->first();
        $patientRole = DB::table('roles')->where('name', 'patient')->first();

        // Gunakan model User agar casting 'hashed' pada password berfungsi
        \App\Models\User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'password' => 'password', // Biarkan model yang hash otomatis
            'role_id' => $doctorRole->id,
        ]);

        \App\Models\User::create([
            'name' => 'Rina Wijaya',
            'email' => 'rina.wijaya@example.com',
            'password' => 'password', // Biarkan model yang hash otomatis
            'role_id' => $patientRole->id,
        ]);

        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password', // Biarkan model yang hash otomatis
            'role_id' => $adminRole->id,
        ]);
    }
}
