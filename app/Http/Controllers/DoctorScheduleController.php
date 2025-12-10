<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        $schedules = DoctorSchedule::with(['doctor.user', 'doctor.hospitalDepartment'])->get();
        return view('doctor-schedules.index', compact('schedules'));
    }

    public function create()
    {
        // Get all doctors with their user and hospital department
        $doctors = Doctor::with(['user', 'hospitalDepartment'])->get();

        return view('doctor-schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
        ]);

        DoctorSchedule::create($validated);

        return redirect()->route('doctor-schedules.index')->with('success', 'Jadwal dokter berhasil dibuat.');
    }

    public function show(DoctorSchedule $doctorSchedule)
    {
        $doctorSchedule->load(['doctor.user', 'doctor.hospitalDepartment']);
        return view('doctor-schedules.show', compact('doctorSchedule'));
    }

    public function edit(DoctorSchedule $doctorSchedule)
    {
        // Get all doctors with their user and hospital department
        $doctors = Doctor::with(['user', 'hospitalDepartment'])->get();

        return view('doctor-schedules.edit', compact('doctorSchedule', 'doctors'));
    }

    public function update(Request $request, DoctorSchedule $doctorSchedule)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1',
        ]);

        $doctorSchedule->update($validated);

        return redirect()->route('doctor-schedules.index')->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy(DoctorSchedule $doctorSchedule)
    {
        $doctorSchedule->delete();

        return redirect()
            ->route('doctor-schedules.index')
            ->with('success', 'Jadwal dokter berhasil dihapus.');
    }

    public function mySchedule()
    {
        // 1. Dapatkan ID Dokter (doctors.id) yang terkait dengan User yang login (users.id)
        $doctor = Doctor::where('user_id', Auth::id())->first();

        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'Profil Dokter tidak ditemukan. Hubungi Admin.');
        }

        $doctorId = $doctor->id;

        $schedules = DoctorSchedule::where('doctor_id', $doctorId)->get();

        $now = Carbon::now();
        $currentTime = $now->format('H:i');
        $currentDayOfWeek = $now->dayOfWeek; // 0 (Sun) - 6 (Sat)

        // Mapping Hari Indonesia ke Integer (0=Minggu, 6=Sabtu)
        $dayMapping = [
            'minggu' => 0, 'senin' => 1, 'selasa' => 2, 'rabu' => 3,
            'kamis' => 4, 'jumat' => 5, 'sabtu' => 6
        ];


        foreach ($schedules as $schedule) {
            $scheduleDayString = strtolower($schedule->day_of_week);
            $scheduleDayOfWeek = $dayMapping[$scheduleDayString] ?? -1; // Ambil nilai integer

            $start = $schedule->start_time;
            $end   = $schedule->end_time;

            $status = 'akan datang';

            // Pengecekan 1: Jika hari ini (menggunakan perbandingan integer)
            if ($scheduleDayOfWeek === $currentDayOfWeek) {
                if ($currentTime >= $start && $currentTime <= $end) {
                    $status = 'berjalan';
                } elseif ($currentTime > $end) {
                    $status = 'selesai';
                }
            }
            // Pengecekan 2: Jika hari jadwal sudah berlalu dalam seminggu
            elseif ($scheduleDayOfWeek < $currentDayOfWeek) {
                $status = 'selesai';
            }

            $schedule->status = $status;
        }

        return view('doctors.schedule.index', compact('schedules'));
    }

    public function queueSchedule($schedule_id)
    {
        // 1. Dapatkan profil dokter yang sedang login
        $doctor = Doctor::where('user_id', Auth::id())->first();
        if (!$doctor) {
            return redirect()->route('doctor.my-schedule')->with('error', 'Akses Ditolak: Profil Dokter tidak ditemukan.');
        }

        $schedule = DoctorSchedule::find($schedule_id);

        if (!$schedule) {
            return redirect()->route('doctor.my-schedule')
                ->with('error', 'Jadwal tidak ditemukan.');
        }

        // 2. Cek Otorisasi: Pastikan jadwal ini milik dokter yang sedang login
        if ($schedule->doctor_id !== $doctor->id) {
            return redirect()->route('doctor.my-schedule')
                ->with('error', 'Akses Ditolak: Jadwal ini bukan milik Anda.');
        }

        // 3. Ambil janji temu, dengan Eager Loading MedicalRecord
        $appointments = Appointment::where('schedule_id', $schedule_id)
            ->orderBy('queue_number', 'asc')
            ->with(['patient.user', 'medicalRecord'])
            ->get();

        return view('doctors.schedule.queue', compact('appointments', 'schedule'));
    }
}
