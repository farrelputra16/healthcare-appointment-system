<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Doctor; // <-- Tambahkan import model Doctor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorAppointmentController extends Controller
{
    /**
     * Memperbarui status Appointment.
     * Menerima: status (completed, checked_in, cancelled)
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        // 1. Dapatkan Profil Dokter yang sedang login (ID Dokter dari tabel 'doctors')
        $doctor = Doctor::where('user_id', Auth::id())->first();

        // Pengecekan 1: Pastikan User yang login adalah Dokter dan memiliki profil di tabel 'doctors'
        if (!$doctor) {
            return redirect()->back()->with('error', 'Akses ditolak. Profil Dokter tidak ditemukan.');
        }

        $loggedInDoctorId = $doctor->id; // Ini adalah ID yang benar (doctors.id)

        // 2. Otorisasi: Pastikan Janji Temu ini milik Dokter yang sedang login
        // Bandingkan appointment->doctor_id (doctors.id) dengan $loggedInDoctorId (doctors.id)
        if ($appointment->doctor_id !== $loggedInDoctorId) {
            return redirect()->back()->with('error', 'Akses ditolak. Janji temu ini bukan untuk jadwal Anda.');
        }

        // 3. Validasi dan Update Status
        $request->validate([
            'status' => 'required|in:scheduled,checked_in,completed,cancelled', // Status yang valid
        ]);

        $newStatus = $request->status;

        $appointment->update(['status' => $newStatus]);

        // 4. Jika status menjadi 'completed', buat draft Medical Record jika belum ada
        if ($newStatus === 'completed') {
            MedicalRecord::firstOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $loggedInDoctorId, // Gunakan ID Dokter yang sudah diverifikasi
                    'notes' => 'Rekam medis siap diisi.',
                    'diagnosis' => 'Belum ada diagnosis.',
                ]
            );
            $message = 'Status Janji Temu diperbarui menjadi Selesai. Catatan Medis draft telah dibuat.';
        } else {
            $message = "Status Janji Temu diperbarui menjadi " . ucfirst($newStatus) . ".";
        }

        return redirect()->back()->with('success', $message);
    }
}
