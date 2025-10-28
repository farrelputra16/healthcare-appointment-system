<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus Foreign Key di tabel Appointment (Jika ada)
        // Periksa apakah tabel appointments memiliki kolom payment_id atau sejenisnya
        Schema::table('appointments', function (Blueprint $table) {
            // Contoh: Jika ada kolom payment_id
            if (Schema::hasColumn('appointments', 'payment_id')) {
                $table->dropConstrainedForeignId('payment_id');
            }
        });

        // 2. Drop Tabel Payments
        Schema::dropIfExists('payments');
    }

    public function down(): void
        // Jika Anda ingin mengembalikan tabel, masukkan skema create_payments_table di sini
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments');
            $table->decimal('amount', 10, 2);
            $table->string('method');
            $table->string('status');
            $table->timestamps();
        });
    }
};
