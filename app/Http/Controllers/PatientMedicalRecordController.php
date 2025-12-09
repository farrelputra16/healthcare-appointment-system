<?php

namespace App\Http\Controllers\Patient; // Perhatikan namespace ini

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord; // Tidak diperlukan karena diambil dari Appointment, tapi baik untuk kejelasan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientMedicalRecordController extends Controller
{
    /**
     * Menampilkan Medical Record untuk Appointment tertentu milik Pasien.
     */
    public function show(Appointment $appointment)
    {
        // 1. Otorisasi: Pastikan janji temu ini milik pasien yang sedang login
        // Appointment->patient_id harus sama dengan ID user yang sedang login
        if ($appointment->patient_id !== Auth::id()) {
            abort(403, 'Akses Ditolak. Janji Temu ini bukan milik Anda.');
        }

        // 2. Ambil Medical Record yang terkait dengan appointment
        // Karena Appointment memiliki relasi belongsTo MedicalRecord (melalui 'appointment_id' di MedicalRecord)
        $record = $appointment->medicalRecord;

        // 3. Cek ketersediaan catatan medis
        if (!$record) {
            // Bisa menggunakan view terpisah atau redirect dengan pesan error
            return redirect()->route('patient.appointments.index')->with('error', 'Catatan Medis untuk janji temu ini belum tersedia atau belum diisi oleh dokter.');
        }

        // 4. Tampilkan view
        return view('patient.medical-records.show', compact('record', 'appointment'));
    }
}
