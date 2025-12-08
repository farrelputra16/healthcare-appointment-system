<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorSchedule;
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
        $doctorId = Auth::id();

        $schedules = DoctorSchedule::where('doctor_id', $doctorId)->get();

        // Waktu sekarang
        $now = Carbon::now();                 // contoh: 2025-12-10 14:30
        $currentDay = strtolower($now->format('l'));  // "wednesday"
        $currentTime = $now->format('H:i');   // "14:30"

        foreach ($schedules as $schedule) {
            $scheduleDay = strtolower($schedule->day);       // misal "monday"
            $start = $schedule->start_time;                  // "09:00"
            $end   = $schedule->end_time;                    // "12:00"

            // Default
            $status = 'akan datang';

            // 1. Jika hari sama
            if ($scheduleDay === $currentDay) {
                if ($currentTime >= $start && $currentTime <= $end) {
                    $status = 'berjalan';
                } elseif ($currentTime > $end) {
                    $status = 'selesai';
                }
            }
            // 2. Jika hari sudah lewat (misal sekarang Rabu, jadwal Senin)
            elseif (Carbon::parse($scheduleDay)->dayOfWeek < $now->dayOfWeek) {
                $status = 'selesai';
            }

            // Tambahkan atribut status ke setiap object schedule
            $schedule->status = $status;
        }

        return view('doctors.schedule.index', compact('schedules'));
    }
}
