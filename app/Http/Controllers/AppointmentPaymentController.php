<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AppointmentPaymentController extends Controller
{
    private $consultationFee;
    private $expiredHours;

    public function __construct()
    {
        $this->consultationFee = config('services.payment.consultation_fee', 50000);
        // FIX: Tambahkan (int) untuk memastikan Carbon menerima integer
        $this->expiredHours = (int) config('services.payment.expired_hours', 24);
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:doctor_schedules,id',
            'appointment_date' => 'required|date|after_or_equal:today',
        ]);

        $doctor = Doctor::with('user')->findOrFail($request->doctor_id);
        $schedule = DoctorSchedule::findOrFail($request->schedule_id);

        Session::put('booking_data', $request->all());

        $queueNumber = Appointment::where('schedule_id', $schedule->id)
                                    ->where('appointment_date', $request->appointment_date)
                                    ->max('queue_number') + 1;

        $price = $this->consultationFee;

        return view('orders.confirm', compact('doctor', 'schedule', 'queueNumber', 'price'));
    }

    public function process()
    {
        $bookingData = Session::get('booking_data');

        if (!Auth::check() || !$bookingData) {
            return redirect()->route('patient.doctors.index')->with('error', 'Sesi pemesanan hilang.');
        }

        $doctor = Doctor::with('user')->findOrFail($bookingData['doctor_id']);
        $patient = Patient::where('user_id', Auth::id())->firstOrFail();
        $schedule = DoctorSchedule::findOrFail($bookingData['schedule_id']);

        $totalAmount = $this->consultationFee;
        $orderNumber = 'MEDIC-' . strtoupper(Str::random(10));

        // 1. Buat Appointment DRAFT (status: payment_pending)
        $queueNumber = Appointment::where('schedule_id', $schedule->id)
                                    ->where('appointment_date', $bookingData['appointment_date'])
                                    ->max('queue_number') + 1;

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'schedule_id' => $schedule->id,
            'appointment_date' => $bookingData['appointment_date'],
            'queue_number' => $queueNumber,
            'status' => 'payment_pending',
            'reason' => $bookingData['reason'] ?? 'Konsultasi Umum',
        ]);

        // 2. Buat Order yang mereferensikan Appointment Draft
        $order = Order::create([
            'user_id' => Auth::id(),
            'appointment_id' => $appointment->id,
            'order_number' => $orderNumber,
            'quantity' => 1,
            'price' => $totalAmount,
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'expired_at' => now()->addHours($this->expiredHours), // Nilai sekarang sudah INTEGER
        ]);

        // 3. Panggil API Payment Gateway (Doovera)
        try {
            $response = Http::withHeaders([
                'X-API-Key' => config('services.payment.api_key'),
                'Accept' => 'application/json',
            ])->post(config('services.payment.base_url') . '/virtual-account/create', [
                'external_id' => $order->order_number,
                'amount' => $order->total_amount,
                'customer_name' => Auth::user()->name,
                'customer_email' => Auth::user()->email,
                'customer_phone' => $patient->phone_number ?? '081234567890',
                'description' => 'Konsultasi ' . $doctor->user->name . ' (' . $order->order_number . ')',
                'expired_duration' => $this->expiredHours,
                'callback_url' => route('orders.success', $order),
                'metadata' => ['appointment_id' => $appointment->id, 'user_id' => Auth::id()],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $order->update(['va_number' => $data['data']['va_number'], 'payment_url' => $data['data']['payment_url']]);
                Session::forget('booking_data');
                return redirect()->route('orders.waiting', $order);
            } else {
                $appointment->delete();
                $order->update(['payment_status' => 'failed']);
                return redirect()->route('patient.doctors.index')->with('error', 'Gagal membuat VA.');
            }
        } catch (\Exception $e) {
            $appointment->delete();
            $order->update(['payment_status' => 'failed']);
            return redirect()->route('patient.doctors.index')->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function waiting(Order $order)
    {
        if ($order->user_id !== Auth::id()) { abort(403); }
        if ($order->isPaid()) { return redirect()->route('orders.success', $order); }
        if ($order->isExpired()) { return redirect()->route('patient.doctors.index')->with('error', 'Pembayaran telah expired.'); }

        return view('orders.waiting', compact('order'));
    }

    public function checkStatus(Order $order)
    {
        if ($order->user_id !== Auth::id()) { abort(403); }

        return response()->json(['status' => $order->payment_status, 'paid_at' => $order->paid_at?->toISOString()]);
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) { abort(403); }
        if (!$order->isPaid()) { return redirect()->route('orders.waiting', $order); }

        return view('orders.success', compact('order'));
    }
}
