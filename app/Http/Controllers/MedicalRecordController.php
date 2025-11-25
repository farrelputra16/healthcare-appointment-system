<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicalRecords = MedicalRecord::with(['patient.user', 'doctor.user', 'appointment'])
            ->latest()
            ->paginate(10);
        
        return view('medical-records.index', compact('medicalRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::with('user')->get();
        $appointments = Appointment::with(['patient.user', 'doctor.user'])->get();
        
        return view('medical-records.create', compact('patients', 'doctors', 'appointments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'patient_id.required' => 'Pasien harus dipilih.',
            'patient_id.exists' => 'Pasien yang dipilih tidak valid.',
            'doctor_id.required' => 'Dokter harus dipilih.',
            'doctor_id.exists' => 'Dokter yang dipilih tidak valid.',
            'appointment_id.required' => 'Janji temu harus dipilih.',
            'appointment_id.exists' => 'Janji temu yang dipilih tidak valid.',
            'diagnosis.required' => 'Diagnosis harus diisi.',
            'diagnosis.string' => 'Diagnosis harus berupa teks.',
            'notes.string' => 'Catatan harus berupa teks.',
        ]);

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
        $doctors = Doctor::with('user')->get();
        $appointments = Appointment::with(['patient.user', 'doctor.user'])->get();
        
        return view('medical-records.edit', compact('medicalRecord', 'patients', 'doctors', 'appointments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'patient_id.required' => 'Pasien harus dipilih.',
            'patient_id.exists' => 'Pasien yang dipilih tidak valid.',
            'doctor_id.required' => 'Dokter harus dipilih.',
            'doctor_id.exists' => 'Dokter yang dipilih tidak valid.',
            'appointment_id.required' => 'Janji temu harus dipilih.',
            'appointment_id.exists' => 'Janji temu yang dipilih tidak valid.',
            'diagnosis.required' => 'Diagnosis harus diisi.',
            'diagnosis.string' => 'Diagnosis harus berupa teks.',
            'notes.string' => 'Catatan harus berupa teks.',
        ]);

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
