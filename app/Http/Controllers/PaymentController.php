<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin: daftar semua pasien yang membuat appointment (berdasarkan seeder/appointments table)
            $appointments = Appointment::with(['patient.user', 'doctor.user'])
                ->orderByDesc('created_at')
                ->get();

            return view('payments.index', [
                'mode' => 'admin',
                'appointments' => $appointments,
            ]);
        }

        // Patient: tampilkan jumlah yang harus dibayar untuk appointment terbaru yang belum dibayar
        $patient = Patient::where('user_id', $user->id)->first();

        $orders = collect();
        if ($patient) {
            $orders = Order::with(['appointment.doctor.user'])
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('payments.index', [
            'mode' => 'patient',
            'orders' => $orders,
        ]);
    }
}
