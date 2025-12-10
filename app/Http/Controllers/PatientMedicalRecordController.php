<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient; // <-- Pastikan model Patient di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientMedicalRecordController extends Controller
{
    /**
     * Menampilkan Medical Record untuk Appointment tertentu milik Pasien.
     */
    public function show(Appointment $appointment)
    {
        // 1. Ambil profil Patient yang terkait dengan User yang sedang login (Auth::id())
        $loggedInPatient = Patient::where('user_id', Auth::id())->first();

        // 2. Cek apakah pengguna memiliki profil pasien
        if (!$loggedInPatient) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki profil pasien yang valid.');
        }

        // 3. Otorisasi: Bandingkan patient_id di appointment dengan ID Patient yang login
        // patient_id (di appointments) HARUS sama dengan $loggedInPatient->id (dari tabel patients)
        if ($appointment->patient_id !== $loggedInPatient->id) {
            abort(403, 'Akses Ditolak. Janji Temu ini bukan milik Anda.');
        }

        // 4. Ambil Medical Record yang terkait
        $record = $appointment->medicalRecord;

        // 5. Cek ketersediaan catatan medis
        if (!$record) {
            return redirect()->route('patient.appointments.index')->with('error', 'Catatan Medis untuk janji temu ini belum tersedia atau belum diisi oleh dokter.');
        }

        // 6. Tampilkan view
        return view('patient.medical-records.show', compact('record', 'appointment'));
    }
}
