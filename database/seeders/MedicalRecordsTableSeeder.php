<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicalRecordsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('medical_records')->insert([
            [
                'patient_id' => 1,
                'doctor_id' => 1,
                'appointment_id' => 1,
                'notes' => 'Pasien datang dengan keluhan nyeri dada ringan.',
                'diagnosis' => 'Angina Pektoris.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
