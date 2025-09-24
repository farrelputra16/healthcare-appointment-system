<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabResultsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lab_results')->insert([
            [
                'medical_record_id' => 1,
                'test_name' => 'Elektrokardiogram (EKG)',
                'result' => 'Hasil EKG menunjukkan ST depresi.',
                'normal_range' => 'Tidak ada ST depresi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
