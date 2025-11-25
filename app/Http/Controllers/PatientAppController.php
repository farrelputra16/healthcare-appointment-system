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
        // Only show doctors that have schedules
        $query = Doctor::with(['user', 'hospitalDepartment'])
            ->whereHas('schedules'); // Only doctors with at least one schedule

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
        // Only show doctors that have schedules
        $doctors = Doctor::with('user')
            ->where('hospital_department_id', $department->id)
            ->whereHas('schedules') // Only doctors with at least one schedule
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
            // FIX: Buat Paginator kosong jika pasien tidak ditemukan
            $appointments = new LengthAwarePaginator(
                [], // Item kosong
                0,  // Total item nol
                10, // Item per halaman (sesuaikan jika perlu)
                1,  // Halaman saat ini
                ['path' => request()->url()] // Opsi path, penting agar link paginasi benar
            );
        } else {
            // Jika pasien ditemukan, ambil janji temu dengan paginasi
            $appointments = $patient->appointments()
                ->with(['doctor.user', 'schedule'])
                ->latest('appointment_date') // Urutkan berdasarkan tanggal janji temu
                ->paginate(10); // paginate() sudah mengembalikan Paginator
        }

        // Tampilkan view
        return view('patient.appointments.index', compact('appointments'));
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

