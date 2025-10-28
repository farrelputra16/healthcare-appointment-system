<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorSchedule;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        // Show all doctor schedules
        $query = DoctorSchedule::with(['doctor.user', 'doctor.hospitalDepartment']);

        // Filter by doctor if provided
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $schedules = $query->get();
        $doctors = Doctor::with('user')->get();

        return view('appointments.index', compact('schedules', 'doctors'));
    }

    public function showAppointments(Request $request, DoctorSchedule $schedule)
    {
        // Get appointment_date from query parameter or session or request
        $appointmentDate = $request->input('appointment_date') 
            ?? $request->query('appointment_date') 
            ?? $request->session()->get('appointment_date');
        
        if (!$appointmentDate) {
            return redirect()->route('appointments.index')->withErrors(['error' => 'Tanggal tidak ditemukan.']);
        }
        
        // Get all appointments for this schedule on this date
        $appointments = Appointment::with(['patient.user'])
            ->where('schedule_id', $schedule->id)
            ->where('appointment_date', $appointmentDate)
            ->orderBy('queue_number', 'asc')
            ->get();

        // Calculate total slots and available slots
        $totalSlots = $schedule->max_patients;
        $bookedSlots = $appointments->count();
        $availableSlots = $totalSlots - $bookedSlots;

        return view('appointments.schedule-appointments', compact('schedule', 'appointments', 'appointmentDate', 'totalSlots', 'bookedSlots', 'availableSlots'));
    }

    public function create(Request $request)
    {
        $doctors = Doctor::with(['user', 'hospitalDepartment'])->get();
        $patients = Patient::with('user')->get();
        
        // Pre-select schedule and date if provided
        $selectedScheduleId = $request->input('schedule_id');
        $selectedDate = $request->input('appointment_date');
        
        return view('appointments.create', compact('doctors', 'patients', 'selectedScheduleId', 'selectedDate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'appointment_date' => 'required|date',
            'reason' => 'nullable|string',
        ]);

        // Get the schedule to check max patients
        $schedule = DoctorSchedule::findOrFail($validated['schedule_id']);
        
        // Check if max patients reached
        $existingCount = Appointment::where('schedule_id', $validated['schedule_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($existingCount >= $schedule->max_patients) {
            return redirect()->back()->withErrors(['error' => 'Jumlah pasien maksimum untuk jadwal ini telah tercapai.'])->withInput();
        }

        // Calculate queue number
        $queueNumber = Appointment::where('schedule_id', $validated['schedule_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->where('status', '!=', 'cancelled')
            ->max('queue_number') ?? 0;
        $queueNumber++;

        $appointment = Appointment::create([
            'doctor_id' => $validated['doctor_id'],
            'patient_id' => $validated['patient_id'],
            'schedule_id' => $validated['schedule_id'],
            'appointment_date' => $validated['appointment_date'],
            'queue_number' => $queueNumber,
            'status' => 'scheduled',
            'reason' => $validated['reason'] ?? null,
        ]);

        // Redirect back to schedule appointments if coming from there
        if ($request->input('redirect_back')) {
            return redirect()->route('appointments.schedule', [
                'schedule' => $validated['schedule_id'],
                'appointment_date' => $validated['appointment_date']
            ])->with('success', 'Janji temu berhasil dibuat.');
        }

        return redirect()->route('appointments.index')->with('success', 'Janji temu berhasil dibuat.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['doctor.user', 'doctor.hospitalDepartment', 'patient.user', 'schedule']);
        return view('appointments.show', compact('appointment'));
    }

    public function updateQueue(Request $request, Appointment $appointment)
    {
        // Log the request for debugging
        \Log::info('Update Queue Request', [
            'appointment_id' => $appointment->id,
            'current_queue' => $appointment->queue_number,
            'new_queue' => $request->queue_number,
            'appointment_date' => $appointment->appointment_date,
            'schedule_id' => $appointment->schedule_id
        ]);

        $validated = $request->validate([
            'queue_number' => 'required|integer|min:1',
        ]);

        $newQueueNumber = $validated['queue_number'];
        $oldQueueNumber = $appointment->queue_number;

        // If trying to change to the same queue number, just redirect
        if ($newQueueNumber == $oldQueueNumber) {
            return redirect()->route('appointments.schedule', ['schedule' => $appointment->schedule_id])
                ->with('appointment_date', $appointment->appointment_date)
                ->with('success', 'Nomor antrian tidak berubah.');
        }

        // Check if new queue number is available
        $existing = Appointment::where('schedule_id', $appointment->schedule_id)
            ->where('appointment_date', $appointment->appointment_date)
            ->where('queue_number', $newQueueNumber)
            ->where('id', '!=', $appointment->id)
            ->first();

        // If there's a conflict, swap the queue numbers
        if ($existing) {
            \Log::info('Queue number conflict detected, swapping queue numbers');
            
            // Swap the queue numbers
            $tempQueue = $oldQueueNumber;
            $appointment->queue_number = $newQueueNumber;
            $appointment->save();
            
            $existing->queue_number = $tempQueue;
            $existing->save();
            
            \Log::info('Queue swapped successfully', [
                'appointment_id' => $appointment->id,
                'new_queue' => $appointment->queue_number,
                'swapped_with' => $existing->id,
                'swapped_queue' => $existing->queue_number
            ]);
        } else {
            // No conflict, just update
            $appointment->queue_number = $newQueueNumber;
            $appointment->save();
            
            \Log::info('Queue updated successfully', [
                'appointment_id' => $appointment->id,
                'new_queue' => $appointment->queue_number
            ]);
        }

        // Redirect back to schedule appointments view with appointment_date in URL
        $scheduleId = $appointment->schedule_id;
        $appointmentDate = $appointment->appointment_date;
        
        return redirect()->to(url()->route('appointments.schedule', ['schedule' => $scheduleId]) . '?appointment_date=' . $appointmentDate)
            ->with('success', 'Nomor antrian berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled',
        ]);

        $appointment->update(['status' => $validated['status']]);

        // Redirect back to schedule appointments view
        $scheduleId = $appointment->schedule_id;
        $appointmentDate = $appointment->appointment_date;
        
        return redirect()->to(url()->route('appointments.schedule', ['schedule' => $scheduleId]) . '?appointment_date=' . $appointmentDate)
            ->with('success', 'Status janji temu berhasil diperbarui.');
    }

    public function destroy(Appointment $appointment)
    {
        // Reorder queue numbers
        $scheduleId = $appointment->schedule_id;
        $appointmentDate = $appointment->appointment_date;
        $deletedQueueNumber = $appointment->queue_number;

        $appointment->delete();

        // Reorder remaining appointments
        $remaining = Appointment::where('schedule_id', $scheduleId)
            ->where('appointment_date', $appointmentDate)
            ->where('queue_number', '>', $deletedQueueNumber)
            ->orderBy('queue_number')
            ->get();

        foreach ($remaining as $index => $apt) {
            $apt->update(['queue_number' => $deletedQueueNumber + $index]);
        }

        // Redirect back to schedule appointments view
        return redirect()->to(url()->route('appointments.schedule', ['schedule' => $scheduleId]) . '?appointment_date=' . $appointmentDate)
            ->with('success', 'Janji temu berhasil dihapus.');
    }
}

