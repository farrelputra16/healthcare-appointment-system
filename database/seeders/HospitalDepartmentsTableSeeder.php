<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalDepartmentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hospital_departments')->insert([
            ['name' => 'Kardiologi', 'description' => 'Departemen yang berfokus pada kesehatan jantung.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ortopedi', 'description' => 'Departemen yang berfokus pada sistem muskuloskeletal.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dermatologi', 'description' => 'Departemen yang berfokus pada kesehatan kulit.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
