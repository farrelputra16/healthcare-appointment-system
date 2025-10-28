<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Appointment; // Tambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handlePayment(Request $request)
    {
        // Log webhook received
        Log::info('Webhook received', $request->all());

        // 1. Verifikasi signature (sesuai kode Anda)
        $signature = $request->header('X-Webhook-Signature');
        $webhookSecret = config('services.payment.webhook_secret');
        $payload = $request->all();
        $expectedSignature = hash_hmac('sha256', json_encode($payload), $webhookSecret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Invalid webhook signature', ['expected' => $expectedSignature, 'received' => $signature]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // 2. Proses webhook
        $event = $request->input('event');
        $data = $request->input('data');

        if ($event === 'payment.success') {
            $externalId = $data['external_id'];
            $order = Order::where('order_number', $externalId)->first();

            if (!$order) {
                Log::warning('Order not found', ['external_id' => $externalId]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            if ($order->isPaid()) {
                Log::info('Payment already processed', ['order_id' => $order->id]);
                return response()->json(['message' => 'Already processed'], 200);
            }

            // --- LOGIKA UTAMA MEDIK: UPDATE ORDER STATUS DAN BUAT APPOINTMENT ---
            $order->update(['payment_status' => 'paid', 'paid_at' => now()]);

            // Di sini Anda HARUS membuat Appointment RESMI.
            // Data booking awal harusnya disimpan di model Order saat proses.
            // Logika untuk membuat Appointment dari data yang disimpan di Order diperlukan di sini.

            // Contoh Placeholder:
            // $bookingData = $order->metadata; // Misal disimpan di metadata

            // Appointment::create([
            //    'patient_id' => $bookingData['patient_id'],
            //    ... data lain
            // ]);
            // $order->update(['appointment_id' => $newAppointment->id]);


            Log::info('Payment success processed', ['order_id' => $order->id]);
            return response()->json(['message' => 'Webhook processed successfully'], 200);
        }

        // ... (Logika payment.failed dan payment.expired tetap sama) ...
        if ($event === 'payment.failed' || $event === 'payment.expired') {
            $externalId = $data['external_id'];
            $order = Order::where('order_number', $externalId)->first();
            if ($order) {
                $order->update(['payment_status' => $event === 'payment.failed' ? 'failed' : 'expired']);
            }
            return response()->json(['message' => 'Webhook processed'], 200);
        }


        return response()->json(['message' => 'Event not handled'], 200);
    }
}
