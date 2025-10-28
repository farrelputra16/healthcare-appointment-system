<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalDepartmentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('hospital_departments')->insert([
            ['name' => 'Kardiologi', 'description' => 'Departemen kesehatan jantung.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ortopedi', 'description' => 'Departemen sistem muskuloskeletal.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dermatologi', 'description' => 'Departemen kesehatan kulit.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Neurologi', 'description' => 'Departemen sistem saraf.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gigi', 'description' => 'Departemen kesehatan gigi dan mulut.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mata', 'description' => 'Departemen kesehatan mata.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Umum', 'description' => 'Dokter umum.', 'created_at' => now(), 'updated_at' => now()], // Tambah Dokter Umum
        ]);
        $this->command->info('Data departemen rumah sakit telah dibuat.');
    }
}
