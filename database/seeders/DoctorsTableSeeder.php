<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('doctors')->insert([
            [
                'user_id' => 1,
                'specialty' => 'Kardiologi',
                'license_number' => 'KD-001',
                'bio' => 'Dokter spesialis jantung berpengalaman.',
                'hospital_department_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
