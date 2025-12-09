<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = MedicalRecord::with(['patient.user', 'doctor.user', 'appointment'])->latest();

        if (!Auth::user()->isAdmin()) {
            $doctorId = Auth::user()->doctor->id ?? null;
            $query->where('doctor_id', $doctorId);
        }

        $medicalRecords = $query->paginate(10);
        
        return view('medical-records.index', compact('medicalRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $appointmentsQuery = Appointment::with(['patient.user', 'doctor.user']);

        if (!Auth::user()->isAdmin()) {
            $doctorId = Auth::user()->doctor->id ?? null;
            $appointmentsQuery->where('doctor_id', $doctorId);
        }

        $appointments = $appointmentsQuery->get();
        
        return view('medical-records.create', compact('patients', 'appointments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $doctorId = Auth::user()->doctor->id ?? null;

        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'patient_id.required' => 'Pasien harus dipilih.',
            'patient_id.exists' => 'Pasien yang dipilih tidak valid.',
            'appointment_id.required' => 'Janji temu harus dipilih.',
            'appointment_id.exists' => 'Janji temu yang dipilih tidak valid.',
            'diagnosis.required' => 'Diagnosis harus diisi.',
            'diagnosis.string' => 'Diagnosis harus berupa teks.',
            'notes.string' => 'Catatan harus berupa teks.',
        ]);

        if (!$doctorId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dokter tidak ditemukan untuk akun ini.');
        }

        $validatedData['doctor_id'] = $doctorId;

        try {
            MedicalRecord::create($validatedData);
            
            return redirect()->route('medical-records.index')
                            ->with('success', 'Data rekam medis berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
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
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        $patients = Patient::with('user')->get();
        $appointmentsQuery = Appointment::with(['patient.user', 'doctor.user']);

        if (!Auth::user()->isAdmin()) {
            $doctorId = Auth::user()->doctor->id ?? null;
            $appointmentsQuery->where('doctor_id', $doctorId);
        }

        $appointments = $appointmentsQuery->get();
        
        return view('medical-records.edit', compact('medicalRecord', 'patients', 'appointments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $doctorId = Auth::user()->doctor->id ?? null;

        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'patient_id.required' => 'Pasien harus dipilih.',
            'patient_id.exists' => 'Pasien yang dipilih tidak valid.',
            'appointment_id.required' => 'Janji temu harus dipilih.',
            'appointment_id.exists' => 'Janji temu yang dipilih tidak valid.',
            'diagnosis.required' => 'Diagnosis harus diisi.',
            'diagnosis.string' => 'Diagnosis harus berupa teks.',
            'notes.string' => 'Catatan harus berupa teks.',
        ]);

        if (!$doctorId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dokter tidak ditemukan untuk akun ini.');
        }

        $validatedData['doctor_id'] = $doctorId;

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
