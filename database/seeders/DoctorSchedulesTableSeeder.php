<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSchedulesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('doctor_schedules')->insert([
            [
                'doctor_id' => 1,
                'day_of_week' => 'Senin',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'max_patients' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
