<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     * Logika index tetap seperti sebelumnya.
     */
    public function index()
    {
        $user = Auth::user();
        $doctorId = $user->doctor->id ?? null;

        if ($user->role->name === 'doctor') {
            if (!$doctorId) {
                return redirect()->route('dashboard')->with('error', 'Profil dokter tidak ditemukan.');
            }
            $completedAppointments = Appointment::where('doctor_id', $doctorId)
                ->where('status', 'completed')
                ->with(['patient.user', 'medicalRecord'])
                ->orderBy('appointment_date', 'desc')
                ->orderBy('queue_number', 'desc')
                ->paginate(15);
            return view('medical-records.doctor-index', ['appointments' => $completedAppointments]);
        }

        $query = MedicalRecord::with(['patient.user', 'doctor.user', 'appointment'])->latest();
        $medicalRecords = $query->paginate(10);
        return view('medical-records.index', compact('medicalRecords'));
    }

    /**
     * Show the form for creating a new resource.
     * (Logika ini sudah benar untuk pre-fill Dokter dan normal Admin)
     */
    public function create(Appointment $appointment = null)
    {
        $user = Auth::user();
        $doctorId = $user->doctor->id ?? null;

        $preselectedAppointmentId = null;
        $preselectedPatientId = null;

        if ($user->role->name === 'doctor' && $appointment && $appointment->exists) {
            if ($appointment->doctor_id !== $doctorId) {
                return redirect()->route('medical-records.index')->with('error', 'Janji temu tidak valid atau bukan milik Anda.');
            }
            $preselectedAppointmentId = $appointment->id;
            $preselectedPatientId = $appointment->patient_id;
            $appointments = collect([$appointment]);
            $patients = collect([$appointment->patient]);
            $doctors = collect();
            return view('medical-records.create', compact('patients', 'appointments', 'preselectedAppointmentId', 'preselectedPatientId', 'doctors'));
        }

        $appointmentsQuery = Appointment::where('status', 'completed')->with(['patient.user', 'doctor.user']);

        if ($user->role->name === 'doctor') {
            if (!$doctorId) return redirect()->route('medical-records.index')->with('error', 'Profil dokter tidak ditemukan.');
            $appointmentsQuery->where('doctor_id', $doctorId);
        } else {
             $appointmentsQuery->where('status', 'completed');
             $doctors = Doctor::with('user')->get();
        }

        $appointments = $appointmentsQuery->get();

        if ($user->isAdmin()) {
            $patients = Patient::with('user')->get();
        } else {
            $patients = $appointments->pluck('patient')->unique('id');
            $doctors = collect();
        }

        return view('medical-records.create', compact('patients', 'appointments', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     * (Logika store tetap sama)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $doctorIdFromProfile = $user->doctor->id ?? null;
        $validatedRules = [
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];

        $doctorIdToUse = null;

        if ($user->isAdmin()) {
            $validatedRules['doctor_id'] = 'required|exists:doctors,id';
            $doctorIdToUse = $request->doctor_id;
        } else {
            $doctorIdToUse = $doctorIdFromProfile;
        }

        $validatedData = $request->validate($validatedRules, [
            'patient_id.required' => 'Pasien harus dipilih.',
            'appointment_id.required' => 'Janji temu harus dipilih.',
            'diagnosis.required' => 'Diagnosis harus diisi.',
            'doctor_id.required' => 'Dokter harus dipilih oleh Admin.',
        ]);

        if (!$doctorIdToUse) {
            return redirect()->back()->withInput()->with('error', 'Akses Ditolak: Profil Dokter tidak ditemukan untuk akun ini.');
        }

        $validatedData['doctor_id'] = $doctorIdToUse;

        try {
            MedicalRecord::create($validatedData);
            return redirect()->route('medical-records.index')->with('success', 'Data rekam medis berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['patient.user', 'doctor.user', 'appointment']);
        return view('medical-records.show', compact('medicalRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     * PERBAIKAN: Hanya mengirim medicalRecord.
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        // Otorisasi: Hanya dokter pemilik atau Admin yang boleh mengedit
        if (!Auth::user()->isAdmin() && $medicalRecord->doctor_id !== (Auth::user()->doctor->id ?? null)) {
            abort(403, 'Anda tidak berhak mengedit rekam medis ini.');
        }
        // HANYA KIRIM medicalRecord, view harus menggunakan input statis
        return view('medical-records.edit', compact('medicalRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $doctorId = Auth::user()->doctor->id ?? null;
        if (!Auth::user()->isAdmin() && $medicalRecord->doctor_id !== $doctorId) {
             abort(403, 'Anda tidak berhak mengupdate rekam medis ini.');
        }

        $validatedData = $request->validate([
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'diagnosis.required' => 'Diagnosis harus diisi.',
            'notes.string' => 'Catatan harus berupa teks.',
        ]);

        if (!$doctorId && !Auth::user()->isAdmin()) {
             return redirect()->back()->with('error', 'Akses ditolak.');
        }

        try {
            $medicalRecord->update($validatedData);
            return redirect()->route('medical-records.index')
                            ->with('success', 'Data rekam medis berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        if (!Auth::user()->isAdmin() && $medicalRecord->doctor_id !== (Auth::user()->doctor->id ?? null)) {
            abort(403, 'Anda tidak berhak menghapus rekam medis ini.');
        }

        try {
            $medicalRecord->delete();
            return redirect()->route('medical-records.index')
                            ->with('success', 'Data rekam medis berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
