<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'user_id' => 2,
                'message' => 'Janji temu Anda dengan Dr. Budi Santoso telah dikonfirmasi.',
                'type' => 'appointment_confirmation',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
