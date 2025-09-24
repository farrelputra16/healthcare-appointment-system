<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('patients')->insert([
            [
                'user_id' => 2,
                'date_of_birth' => '1990-05-15',
                'phone_number' => '081234567890',
                'address' => 'Jl. Jendral Sudirman No. 10, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
