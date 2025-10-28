<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relasi: Satu Departemen memiliki banyak Dokter
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'hospital_department_id');
    }
}
