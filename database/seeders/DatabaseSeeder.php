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
            RolesTableSeeder::class, // Harus di atas
            UsersTableSeeder::class,
            HospitalDepartmentsTableSeeder::class,
            DoctorsTableSeeder::class,
            PatientsTableSeeder::class,
            DoctorSchedulesTableSeeder::class,
            AppointmentsTableSeeder::class,
            PaymentsTableSeeder::class,
            MedicalRecordsTableSeeder::class,
            PrescriptionsTableSeeder::class,
            NotificationsTableSeeder::class,
            InsuranceTableSeeder::class,
            LabResultsTableSeeder::class,
        ]);
    }
}
