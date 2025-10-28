<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('set null'); // Link ke Appointment
            $table->string('order_number')->unique();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_status')->default('pending'); // pending, paid, failed, expired
            $table->string('va_number')->nullable();
            $table->string('payment_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
