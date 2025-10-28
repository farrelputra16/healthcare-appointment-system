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

        // 1. Verifikasi signature
        $webhookSecret = config('services.payment.webhook_secret');
        $payload = file_get_contents('php://input');
        
        // Try to get signature from header first, then from payload
        $signature = $request->header('X-Webhook-Signature');
        if (!$signature) {
            $payloadData = json_decode($payload, true);
            $signature = $payloadData['signature'] ?? null;
        }
        
        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        if (!$signature || !hash_equals($expectedSignature, $signature)) {
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

            // Update ORDER menjadi paid
            $order->update(['payment_status' => 'paid', 'paid_at' => now()]);

            // Jika order terkait appointment draft, update payment_status appointment
            if ($order->appointment_id) {
                $appointment = Appointment::find($order->appointment_id);
                if ($appointment) {
                    $appointment->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                        'status' => $appointment->status === 'payment_pending' ? 'scheduled' : $appointment->status,
                    ]);
                }
            }


            Log::info('Payment success processed', ['order_id' => $order->id]);
            return response()->json(['message' => 'Webhook processed successfully'], 200);
        }

        if ($event === 'payment.expired' || $event === 'payment.cancelled') {
            $externalId = $data['external_id'];
            $order = Order::where('order_number', $externalId)->first();
            if ($order) {
                $order->update(['payment_status' => $event === 'payment.cancelled' ? 'cancelled' : 'expired']);
                if ($order->appointment_id) {
                    $appointment = Appointment::find($order->appointment_id);
                    if ($appointment) {
                        $appointment->update([
                            'payment_status' => $event === 'payment.cancelled' ? 'cancelled' : 'expired',
                            'status' => 'cancelled',
                        ]);
                    }
                }
            }
            return response()->json(['message' => 'Webhook processed'], 200);
        }


        return response()->json(['message' => 'Event not handled'], 200);
    }
}
