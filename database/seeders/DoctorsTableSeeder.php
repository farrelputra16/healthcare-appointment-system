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
        // Dapatkan ID Peran Dokter
        $doctorRole = Role::where('name', 'doctor')->first();
        if (!$doctorRole) {
            $this->command->error('Peran "doctor" tidak ditemukan. Jalankan RolesTableSeeder terlebih dahulu.');
            return;
        }

        // Dapatkan ID Departemen
        $departments = HospitalDepartment::pluck('id', 'name')->all(); // Ambil ID berdasarkan nama
        if (empty($departments)) {
            $this->command->error('Departemen tidak ditemukan. Jalankan HospitalDepartmentsTableSeeder terlebih dahulu.');
            return;
        }

        // Data Uji Dokter yang akan dibuat (15 Dokter)
        $doctorsToCreate = [
            // Kardiologi (3)
            ['name' => 'Dr. Budi Santoso, Sp.JP', 'email' => 'budi.dr@test.app', 'specialty' => 'Kardiologi', 'dept_name' => 'Kardiologi'],
            ['name' => 'Dr. Cici Amelia, Sp.JP', 'email' => 'cici.dr@test.app', 'specialty' => 'Kardiologi', 'dept_name' => 'Kardiologi'],
            ['name' => 'Dr. Doni Nugraha, Sp.JP', 'email' => 'doni.dr@test.app', 'specialty' => 'Kardiologi', 'dept_name' => 'Kardiologi'],

            // Ortopedi (3)
            ['name' => 'Dr. Eka Setiawan, Sp.OT', 'email' => 'eka.dr@test.app', 'specialty' => 'Ortopedi', 'dept_name' => 'Ortopedi'],
            ['name' => 'Dr. Fira Hasanah, Sp.OT', 'email' => 'fira.dr@test.app', 'specialty' => 'Ortopedi', 'dept_name' => 'Ortopedi'],
            ['name' => 'Dr. Gilang Ramadhan, Sp.OT', 'email' => 'gilang.dr@test.app', 'specialty' => 'Ortopedi', 'dept_name' => 'Ortopedi'],

            // Dermatologi (2)
            ['name' => 'Dr. Hera Susanti, Sp.KK', 'email' => 'hera.dr@test.app', 'specialty' => 'Dermatologi', 'dept_name' => 'Dermatologi'],
            ['name' => 'Dr. Ivan Maulana, Sp.KK', 'email' => 'ivan.dr@test.app', 'specialty' => 'Dermatologi', 'dept_name' => 'Dermatologi'],

            // Neurologi (2)
            ['name' => 'Dr. Joko Anwar, Sp.N', 'email' => 'joko.dr@test.app', 'specialty' => 'Neurologi', 'dept_name' => 'Neurologi'],
            ['name' => 'Dr. Kartika Putri, Sp.N', 'email' => 'kartika.dr@test.app', 'specialty' => 'Neurologi', 'dept_name' => 'Neurologi'],

            // Gigi (2)
            ['name' => 'Drg. Lina Marlina', 'email' => 'lina.drg@test.app', 'specialty' => 'Dokter Gigi', 'dept_name' => 'Gigi'],
            ['name' => 'Drg. Mahmud Ibrahim', 'email' => 'mahmud.drg@test.app', 'specialty' => 'Dokter Gigi', 'dept_name' => 'Gigi'],

            // Mata (2)
            ['name' => 'Dr. Nina Kusuma, Sp.M', 'email' => 'nina.dr@test.app', 'specialty' => 'Mata', 'dept_name' => 'Mata'],
            ['name' => 'Dr. Omar Abdullah, Sp.M', 'email' => 'omar.dr@test.app', 'specialty' => 'Mata', 'dept_name' => 'Mata'],

            // Umum (1)
            ['name' => 'Dr. Prita Wulandari', 'email' => 'prita.dr@test.app', 'specialty' => 'Dokter Umum', 'dept_name' => 'Umum'],
        ];

        $createdCount = 0;
        foreach ($doctorsToCreate as $i => $data) {
            // Pastikan departemen ada
            if (!isset($departments[$data['dept_name']])) {
                $this->command->warn("Departemen '{$data['dept_name']}' tidak ditemukan, dokter '{$data['name']}' dilewati.");
                continue;
            }

            // 1. Buat Akun User untuk Dokter
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'), // Password default
                'role_id' => $doctorRole->id,
            ]);

            // 2. Buat Entri Dokter
            Doctor::create([
                'user_id' => $user->id,
                'hospital_department_id' => $departments[$data['dept_name']], // Ambil ID dinamis
                'specialty' => $data['specialty'],
                'license_number' => 'LCN-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT), // Buat license unik
                'bio' => 'Dokter spesialis ' . $data['specialty'] . ' dengan dedikasi tinggi untuk pasien.'
            ]);
            $createdCount++;
        }

        $this->command->info('Total ' . $createdCount . ' Dokter telah dibuat.');
    }
}
