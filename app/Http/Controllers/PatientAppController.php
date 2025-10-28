<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class PatientAppController extends Controller
{
    /**
     * Menampilkan daftar semua dokter dan fitur pencarian.
     */
    public function index(Request $request)
    {
        $query = Doctor::with('hospitalDepartment');

        if ($request->filled('search')) {
            $query->where('specialty', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $doctors = $query->paginate(10);

        return view('patient.doctors.index', compact('doctors'));
    }

    /**
     * Menampilkan jadwal spesifik dokter.
     */
    public function showSchedule(Doctor $doctor)
    {
        // Ambil jadwal dokter untuk 7 hari ke depan
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
                                    ->get()
                                    ->groupBy('day_of_week'); // Kelompokkan berdasarkan hari

        return view('patient.doctors.schedule', compact('doctor', 'schedules'));
    }

    /**
     * Memproses permintaan janji temu.
     */
    public function bookAppointment(Request $request)
    {
        // Validasi yang sama seperti di PaymentController::confirm, plus logika antrian
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        // Redirect ke halaman konfirmasi
        return redirect()->route('orders.confirm', $request->all());
    }
    public function myAppointments()
    {
        // 1. Dapatkan model Patient dari user yang sedang login
        $patient = Patient::where('user_id', Auth::id())->firstOrFail();

        // 2. Ambil janji temu dengan relasi Doctor dan Schedule
        $appointments = $patient->appointments()
                                ->with(['doctor.user', 'schedule']) // Load data dokter dan jadwal
                                ->latest() // Tampilkan yang terbaru di atas
                                ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    public function calculateQueue(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'appointment_date' => 'required|date',
        ]);

        $queueNumber = Appointment::where('schedule_id', $request->schedule_id)
                                ->where('appointment_date', $request->appointment_date)
                                ->max('queue_number');

        // Jika tidak ada antrian, nomor antrian dimulai dari 1. Jika ada, +1 dari maksimal.
        $nextQueue = ($queueNumber === null) ? 1 : $queueNumber + 1;

        // Mengembalikan respons JSON
        return response()->json([
            'success' => true,
            'queue_number' => $nextQueue,
            'message' => "Anda akan mendapatkan nomor antrian ke-$nextQueue."
        ]);
    }
}
