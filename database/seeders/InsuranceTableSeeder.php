<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsuranceTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('insurance')->insert([
            [
                'patient_id' => 1,
                'provider_name' => 'Asuransi Sehat Bersama',
                'policy_number' => 'ASB-001-2025',
                'coverage_details' => 'Meliputi biaya rawat inap dan rawat jalan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
