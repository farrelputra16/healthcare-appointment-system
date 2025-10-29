<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorSchedule;
use App\Models\Doctor;

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
}

