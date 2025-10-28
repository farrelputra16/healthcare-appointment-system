<?php

namespace Database\Seeders;
use App\Models\Doctor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoctorSchedulesTableSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::with('hospitalDepartment')->get(); // Ambil semua dokter
        $schedules = [];
        $daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        if ($doctors->isEmpty()) {
             $this->command->warn('Tidak ada dokter ditemukan untuk dibuatkan jadwal. Jalankan DoctorsTableSeeder dahulu.');
             return;
        }

        foreach ($doctors as $doctor) {
            // Pilih 3 hari acak untuk praktik
            shuffle($daysOfWeek);
            $practiceDays = array_slice($daysOfWeek, 0, 3);

            // Tentukan kuota berdasarkan spesialisasi (contoh)
            $maxPatients = ($doctor->specialty === 'Kardiologi' || $doctor->specialty === 'Neurologi') ? 10 : 15;
             if ($doctor->specialty === 'Dokter Gigi' || $doctor->specialty === 'Mata') $maxPatients = 20;


            foreach ($practiceDays as $day) {
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

        // Hapus jadwal lama sebelum insert baru (opsional, tapi disarankan)
       // DB::table('doctor_schedules')->truncate();

        // Insert jadwal baru
        DB::table('doctor_schedules')->insert($schedules);

        $this->command->info('Total ' . count($schedules) . ' jadwal telah dibuat untuk ' . $doctors->count() . ' dokter.');
    }
}
