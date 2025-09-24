<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrescriptionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('prescriptions')->insert([
            [
                'medical_record_id' => 1,
                'medication_name' => 'Nitroglycerin',
                'dosage' => '0.4 mg',
                'instructions' => 'Diletakkan di bawah lidah saat nyeri dada.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
