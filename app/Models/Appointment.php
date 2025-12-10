<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorSchedule; // <-- Tambahkan import ini

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'schedule_id',
        'appointment_date',
        'queue_number',
        'status',
        'reason',
    ];

    // TAMBAHKAN ATAU MODIFIKASI ARRAY $casts INI
    protected $casts = [
        // Mengubah string 'appointment_date' menjadi objek Carbon
        'appointment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
        // Pastikan relasi ini menggunakan Model DoctorSchedule yang sudah diimpor
        return $this->belongsTo(DoctorSchedule::class, 'schedule_id');
    }
    public function medicalRecord()
{
    // Karena medical_records memiliki appointment_id
    return $this->hasOne(\App\Models\MedicalRecord::class, 'appointment_id');
}
}
