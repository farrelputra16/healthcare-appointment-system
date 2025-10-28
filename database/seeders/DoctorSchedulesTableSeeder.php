<?php

namespace Database\Seeders;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSchedulesTableSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::all();
        $schedules = [];

        foreach ($doctors as $doctor) {
            // Hari kerja yang akan digunakan
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
            $maxPatients = ($doctor->specialty === 'Kardiologi') ? 10 : 20;

            foreach ($days as $day) {
                // Jadwal Pagi (08:00 - 12:00)
                $schedules[] = [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00:00',
                    'end_time' => '12:00:00',
                    'max_patients' => $maxPatients,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Jadwal Sore (14:00 - 17:00)
                 $schedules[] = [
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '14:00:00',
                    'end_time' => '17:00:00',
                    'max_patients' => $maxPatients,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('doctor_schedules')->insert($schedules);
        $this->command->info('Total ' . count($schedules) . ' Jadwal telah dibuat untuk semua dokter.');
    }
}
