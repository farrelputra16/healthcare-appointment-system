<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Permission;

class PermissionRoleTableSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $doctorRole = Role::where('name', 'doctor')->first();
        $patientRole = Role::where('name', 'patient')->first();

        $manageUsers = Permission::where('name', 'manage-users')->first();
        $viewPatients = Permission::where('name', 'view-patients')->first();
        $viewDashboard = Permission::where('name', 'view-dashboard')->first();

        // Berikan semua izin kepada admin
        $adminRole->permissions()->attach([$manageUsers->id, $viewPatients->id, $viewDashboard->id]);

        // Berikan izin tertentu kepada dokter
        $doctorRole->permissions()->attach([$viewPatients->id, $viewDashboard->id]);

        // Berikan izin hanya untuk melihat dashboard kepada pasien
        $patientRole->permissions()->attach([$viewDashboard->id]);
    }
}
