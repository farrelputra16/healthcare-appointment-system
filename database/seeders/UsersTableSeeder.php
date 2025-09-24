<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        $doctorRole = DB::table('roles')->where('name', 'doctor')->first();
        $patientRole = DB::table('roles')->where('name', 'patient')->first();

        DB::table('users')->insert([
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => Hash::make('password'),
                'role_id' => $doctorRole->id, // Menggunakan ID dari peran 'doctor'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rina Wijaya',
                'email' => 'rina.wijaya@example.com',
                'password' => Hash::make('password'),
                'role_id' => $patientRole->id, // Menggunakan ID dari peran 'patient'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id, // Menggunakan ID dari peran 'admin'
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
