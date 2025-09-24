<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('appointments')->insert([
            [
                'patient_id' => 1,
                'doctor_id' => 1,
                'schedule_id' => 1,
                'appointment_date' => '2025-09-25',
                'queue_number' => 1,
                'status' => 'scheduled',
                'reason' => 'Konsultasi rutin.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
