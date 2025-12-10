<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'notes',
        'diagnosis',
    ];

    // TAMBAH: Mengonversi kolom waktu menjadi objek Carbon (DateTime)
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // Jika MedicalRecord punya kolom date/time lain, tambahkan di sini
    ];

    // Relasi ke PASIEN
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // Relasi ke DOKTER (profil dokter)
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // Relasi ke APPOINTMENT
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
