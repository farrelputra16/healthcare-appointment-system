<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hospital_department_id',
        'specialty',
        'license_number',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hospitalDepartment()
    {
        return $this->belongsTo(HospitalDepartment::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
