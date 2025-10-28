<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment; // Import model Appointment

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'appointment_id', // Diubah dari 'product_id'
        'order_number',
        'quantity',
        'price',
        'total_amount',
        'payment_status',
        'va_number',
        'payment_url',
        'paid_at',
        'expired_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'quantity' => 'integer',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        // Relasi ke Appointment
        return $this->belongsTo(Appointment::class);
    }

    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isExpired()
    {
        return $this->payment_status === 'expired' ||
               ($this->expired_at && now()->isAfter($this->expired_at));
    }
}
