<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Role;
use App\Models\HospitalDepartment;

class DoctorsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Dapatkan ID Peran Dokter dan ID Departemen
        $doctorRole = Role::where('name', 'doctor')->first();
        $cardioDept = HospitalDepartment::where('name', 'Kardiologi')->first();
        $orthoDept = HospitalDepartment::where('name', 'Ortopedi')->first();
        $dermaDept = HospitalDepartment::where('name', 'Dermatologi')->first();

        // Data Uji Dokter yang akan dibuat
        $doctorsToCreate = [
            // Kardiologi
            ['name' => 'Dr. Budi Santoso, Sp.JP', 'email' => 'budi.dr@test.app', 'specialty' => 'Kardiologi', 'dept' => $cardioDept],
            ['name' => 'Dr. Cici Amelia, Sp.JP', 'email' => 'cici.dr@test.app', 'specialty' => 'Kardiologi', 'dept' => $cardioDept],
            ['name' => 'Dr. Doni Nugraha, Sp.JP', 'email' => 'doni.dr@test.app', 'specialty' => 'Kardiologi', 'dept' => $cardioDept],

            // Ortopedi
            ['name' => 'Dr. Eka Setiawan, Sp.OT', 'email' => 'eka.dr@test.app', 'specialty' => 'Ortopedi', 'dept' => $orthoDept],
            ['name' => 'Dr. Fira Hasanah, Sp.OT', 'email' => 'fira.dr@test.app', 'specialty' => 'Ortopedi', 'dept' => $orthoDept],

            // Dermatologi
            ['name' => 'Dr. Galih Pratama, Sp.KK', 'email' => 'galih.dr@test.app', 'specialty' => 'Dermatologi', 'dept' => $dermaDept],
            ['name' => 'Dr. Hera Susanti, Sp.KK', 'email' => 'hera.dr@test.app', 'specialty' => 'Dermatologi', 'dept' => $dermaDept],
        ];

        foreach ($doctorsToCreate as $i => $data) {
            // 1. Buat Akun User untuk Dokter
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role_id' => $doctorRole->id,
            ]);

            // 2. Buat Entri Dokter
            Doctor::create([
                'user_id' => $user->id,
                'hospital_department_id' => $data['dept']->id,
                'specialty' => $data['specialty'],
                'license_number' => 'LCN-' . ($i + 1),
                'bio' => 'Dokter spesialis ' . $data['specialty'] . ' berpengalaman dan siap melayani.'
            ]);
        }

        $this->command->info('Total ' . count($doctorsToCreate) . ' Dokter telah dibuat.');
    }
}
