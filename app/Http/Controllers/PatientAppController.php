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
use Illuminate\Pagination\LengthAwarePaginator;

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
                // TAMBAH: Memuat relasi medicalRecord untuk check status di view
                ->with(['doctor.user', 'schedule', 'medicalRecord'])
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

        // PERBAIKAN UTAMA: Hapus filter status. Kita butuh MAX queue_number yang pernah dikeluarkan.
        $queueNumber = Appointment::where('schedule_id', $request->schedule_id)
            ->where('appointment_date', $request->appointment_date)
            ->max('queue_number');

        // Jika tidak ada antrian (null), mulai dari 1. Jika ada (misalnya 2), lanjutkan ke 3.
        $nextQueue = ($queueNumber === null) ? 1 : $queueNumber + 1;

        return response()->json([
            'success' => true,
            'queue_number' => $nextQueue,
        ]);
    }

    /**
     * Membatalkan Janji Temu Pasien yang sedang login.
     */
    public function cancelAppointment(Appointment $appointment)
    {
        // 1. Otorisasi: Pastikan janji temu ini milik pasien yang sedang login
        $patient = Patient::where('user_id', Auth::id())->first();

        if (!$patient || $appointment->patient_id !== $patient->id) {
            return redirect()->back()->with('error', 'Akses ditolak. Janji temu ini bukan milik Anda.');
        }

        // 2. Batasi Pembatalan: Hanya boleh membatalkan jika statusnya 'scheduled'
        if ($appointment->status !== 'scheduled') {
            return redirect()->back()->with('error', 'Janji temu hanya dapat dibatalkan jika statusnya SCHEDULED.');
        }

        // 3. Update Status
        try {
            $appointment->update(['status' => 'cancelled']);

            // Opsional: Implementasikan logika refund jika pembayaran sudah dilakukan
            // ...

            return redirect()->route('patient.appointments.index')->with('success', 'Janji temu berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membatalkan janji temu karena kesalahan sistem.');
        }
    }
}
