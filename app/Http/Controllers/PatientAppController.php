<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\HospitalDepartment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator; // <-- TAMBAHKAN USE STATEMENT INI

class PatientAppController extends Controller
{
    /**
     * Menampilkan daftar dokter, dikelompokkan berdasarkan departemen.
     */
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'hospitalDepartment']);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('specialty', 'like', $searchTerm)
                    ->orWhereHas('user', function ($uq) use ($searchTerm) {
                        $uq->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('hospitalDepartment', function ($dq) use ($searchTerm) {
                        $dq->where('name', 'like', $searchTerm);
                    });
            });
        }

        $doctors = $query->get();
        $groupedDoctors = $doctors->groupBy('hospitalDepartment.name');

        return view('patient.doctors.index', compact('groupedDoctors'));
    }

    /**
     * Menampilkan semua dokter dalam satu departemen.
     */
    public function showDepartment(HospitalDepartment $department)
    {
        $doctors = Doctor::with('user')
            ->where('hospital_department_id', $department->id)
            ->paginate(12);

        return view('patient.doctors.department', compact('department', 'doctors'));
    }

    /**
     * Menampilkan jadwal spesifik dokter.
     */
    public function showSchedule(Doctor $doctor)
    {
        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->get()
            ->groupBy('day_of_week');

        return view('patient.doctors.schedule', compact('doctor', 'schedules'));
    }

    /**
     * Mengalihkan permintaan booking ke halaman konfirmasi pembayaran.
     */
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        return redirect()->route('orders.confirm', $request->all());
    }

    /**
     * Menampilkan daftar semua janji temu untuk Pasien yang sedang login.
     * Handle kasus jika pasien belum ada atau tidak punya janji temu.
     */
    public function myAppointments()
    {
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient) {
            // Create patient record if it doesn't exist
            $patient = Patient::create([
                'user_id' => Auth::id(),
                'date_of_birth' => null,
                'phone_number' => null,
                'address' => null,
            ]);
        }

        // Get appointments with pagination
        $appointments = $patient->appointments()
            ->with(['doctor.user', 'schedule', 'order'])
            ->latest('appointment_date')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * Cancel an appointment
     */
    public function cancelAppointment(Request $request, Appointment $appointment)
    {
        // Check if the appointment belongs to the current user
        $patient = Patient::where('user_id', Auth::id())->first();
        
        if (!$patient) {
            return redirect()->back()->with('error', 'Patient record not found.');
        }
        
        if ($appointment->patient_id !== $patient->id) {
            abort(403, 'Unauthorized');
        }

        // Only allow cancellation for scheduled or payment_pending appointments
        if (!in_array($appointment->status, ['scheduled', 'payment_pending'])) {
            return redirect()->back()->with('error', 'Appointment cannot be cancelled.');
        }

        // Update appointment status
        $appointment->update([
            'status' => 'cancelled',
            'payment_status' => 'cancelled'
        ]);

        // If there's an associated order, update it too
        if ($appointment->order) {
            $appointment->order->update([
                'payment_status' => 'cancelled'
            ]);
        }

        return redirect()->back()->with('success', 'Appointment cancelled successfully.');
    }

    /**
     * Menghitung nomor antrian yang akan didapatkan pasien saat ini (untuk AJAX).
     */
    public function calculateQueue(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'appointment_date' => 'required|date',
        ]);

        $queueNumber = Appointment::where('schedule_id', $request->schedule_id)
            ->where('appointment_date', $request->appointment_date)
            ->whereIn('status', ['scheduled', 'payment_pending'])
            ->max('queue_number');

        $nextQueue = ($queueNumber === null) ? 1 : $queueNumber + 1;

        return response()->json([
            'success' => true,
            'queue_number' => $nextQueue,
        ]);
    }
}

