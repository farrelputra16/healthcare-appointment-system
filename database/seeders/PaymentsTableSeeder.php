<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'appointment_id' => 1,
                'amount' => 250000.00,
                'method' => 'credit_card',
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
