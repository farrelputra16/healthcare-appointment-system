<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Order;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'schedule_id',        // <-- HARUS ADA (Memperbaiki error utama)
        'appointment_date',   // <-- HARUS ADA (Menggantikan 'scheduled_at')
        'queue_number',       // <-- HARUS ADA
        'status',
        'reason',             // <-- HARUS ADA
        'payment_status',
        'paid_at',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'schedule_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
