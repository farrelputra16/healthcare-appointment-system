<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'amount',
        'method',
        'status',
        'payment_url',
        'va_number',
        'expired_at',
        'paid_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isExpired(): bool
    {
        return $this->expired_at && now()->greaterThan($this->expired_at);
    }
}
