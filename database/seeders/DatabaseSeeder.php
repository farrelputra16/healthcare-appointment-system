<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // --- MASTER DATA & RBAC INTI (HARUS PERTAMA) ---
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            PermissionRoleTableSeeder::class, // Membutuhkan Roles & Permissions
            HospitalDepartmentsTableSeeder::class, // Data Departemen

            // --- PENGGUNA (USERS) ---
            UsersTableSeeder::class, // Data Dasar Pengguna

            // --- ENTITAS MEDIS UTAMA ---
            DoctorsTableSeeder::class, // Membutuhkan Users & Departments
            PatientsTableSeeder::class, // Membutuhkan Users

            // --- JADWAL & APPOINTMENT ---
            DoctorSchedulesTableSeeder::class, // Membutuhkan Doctors
            AppointmentsTableSeeder::class, // Membutuhkan Patients, Doctors, Schedules

            // --- REKAM MEDIS & TURUNANNYA ---
            MedicalRecordsTableSeeder::class, // Membutuhkan Patients, Doctors, Appointments
            PrescriptionsTableSeeder::class, // Membutuhkan MedicalRecords
            LabResultsTableSeeder::class, // Membutuhkan MedicalRecords

            // --- DATA KEUNGAN & PENDUKUNG ---
            InsuranceTableSeeder::class, // Membutuhkan Patients
            NotificationsTableSeeder::class, // Membutuhkan Users
        ]);
    }
}
