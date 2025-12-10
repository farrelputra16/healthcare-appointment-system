<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Method untuk Dashboard Dokter
    public function showDoctorDashboard()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        $data = [
            'todayAppointmentsCount' => 0,
            'recordsNeededCount' => 0,
            'doctor' => $doctor, // Kirimkan objek dokter
        ];

        if ($doctor) {
            $data['todayAppointmentsCount'] = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', Carbon::today())
                ->whereIn('status', ['scheduled', 'checked_in'])
                ->count();

            $data['recordsNeededCount'] = Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'completed')
                ->whereDoesntHave('medicalRecord')
                ->count();
        }

        // Arahkan ke view khusus Dokter
        return view('dashboard.doctor', $data);
    }

    // Method untuk Dashboard Admin
    public function showAdminDashboard()
    {
        // Di sini Anda bisa memuat ringkasan data Admin (total users, total revenue, etc.)
        $data = []; // Misalnya $data['totalUsers'] = \App\Models\User::count();

        // Arahkan ke view khusus Admin
        return view('dashboard.admin', $data);
    }
}
