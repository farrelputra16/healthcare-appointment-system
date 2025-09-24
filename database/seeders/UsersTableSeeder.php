<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rina Wijaya',
                'email' => 'rina.wijaya@example.com',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
