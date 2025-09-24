<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('permissions')->insert([
            ['name' => 'manage-users', 'display_name' => 'Manage Users', 'description' => 'User can create, read, update, and delete users.'],
            ['name' => 'view-patients', 'display_name' => 'View Patients', 'description' => 'User can view patient records.'],
            ['name' => 'view-dashboard', 'display_name' => 'View Dashboard', 'description' => 'User can access the dashboard.'],
        ]);
    }
}
