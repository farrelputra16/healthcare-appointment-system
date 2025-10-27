<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with([
            'appointment.doctor.user',
            'appointment.patient.user'
        ])->latest()->get();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $appointments = Appointment::with(['doctor.user', 'patient.user'])->get();
        return view('payments.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string',
            'status' => 'required|string',
        ]);

        Payment::create($request->all());
        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    public function edit(Payment $payment)
    {
        $appointments = Appointment::with(['doctor.user', 'patient.user'])->get();
        return view('payments.edit', compact('payment', 'appointments'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string',
            'status' => 'required|string',
        ]);

        $payment->update($request->all());
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function waiting(Payment $payment)
    {
        if ($payment->isPaid()) {
            return redirect()->route('payments.success', $payment);
        }

        if ($payment->isExpired()) {
            return redirect()->route('payments.index')->with('error', 'Pembayaran sudah expired.');
        }

        return view('payments.waiting', compact('payment'));
    }

    public function success(Payment $payment)
    {
        if (!$payment->isPaid()) {
            return redirect()->route('payments.waiting', $payment);
        }

        return view('payments.success', compact('payment'));
    }

    public function webhook(Request $request)
    {
        // Log webhook received
        Log::info('Payment webhook received', $request->all());

        // Verify webhook signature
        $signature = $request->header('X-Webhook-Signature');
        $webhookSecret = config('services.payment.webhook_secret');
        
        // Get payload and calculate expected signature
        $payload = $request->all();
        $expectedSignature = hash_hmac('sha256', json_encode($payload), $webhookSecret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Invalid webhook signature', [
                'expected' => $expectedSignature,
                'received' => $signature,
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Process webhook
        $event = $request->input('event');
        $data = $request->input('data');

        // Handle payment.success event
        if ($event === 'payment.success') {
            $externalId = $data['external_id'];
            
            // Extract payment ID from external_id (format: PAY-{id}-{timestamp} or PAY-{id})
            if (str_contains($externalId, '-')) {
                $parts = explode('-', $externalId);
                $paymentId = $parts[1]; // Get the payment ID part
            } else {
                $paymentId = str_replace('PAY-', '', $externalId);
            }
            
            // Find payment by ID first, then by VA number as fallback
            $payment = Payment::where('id', $paymentId)->first();
            
            if (!$payment && isset($data['va_number'])) {
                $payment = Payment::where('va_number', $data['va_number'])->first();
            }

            if (!$payment) {
                Log::warning('Payment not found', ['external_id' => $externalId]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Check if already processed (idempotency)
            if ($payment->isPaid()) {
                Log::info('Payment already processed', ['payment_id' => $payment->id]);
                return response()->json(['message' => 'Already processed'], 200);
            }

            // Update payment status
            $payment->update([
                'status' => 'paid',
                'paid_at' => $data['paid_at'] ?? now(),
            ]);

            Log::info('Payment success processed', [
                'payment_id' => $payment->id,
                'external_id' => $externalId,
                'amount' => $payment->amount,
            ]);

            return response()->json(['message' => 'Webhook processed successfully'], 200);
        }

        // Handle payment.failed event
        if ($event === 'payment.failed') {
            $externalId = $data['external_id'];
            
            $payment = Payment::where('va_number', $data['va_number'] ?? null)->first();
            
            if ($payment) {
                $payment->update(['status' => 'failed']);
                Log::info('Payment failed processed', ['payment_id' => $payment->id]);
            }

            return response()->json(['message' => 'Webhook processed'], 200);
        }

        // Handle payment.expired event
        if ($event === 'payment.expired') {
            $externalId = $data['external_id'];
            
            $payment = Payment::where('va_number', $data['va_number'] ?? null)->first();
            
            if ($payment) {
                $payment->update(['status' => 'expired']);
                Log::info('Payment expired processed', ['payment_id' => $payment->id]);
            }

            return response()->json(['message' => 'Webhook processed'], 200);
        }

        // Handle payment.cancelled event
        if ($event === 'payment.cancelled') {
            $externalId = $data['external_id'];
            
            $payment = Payment::where('va_number', $data['va_number'] ?? null)->first();
            
            if ($payment) {
                $payment->update(['status' => 'cancelled']);
                Log::info('Payment cancelled processed', ['payment_id' => $payment->id]);
            }

            return response()->json(['message' => 'Webhook processed'], 200);
        }

        return response()->json(['message' => 'Event not handled'], 200);
    }


    public function pay(Payment $payment)
    {
        // Check if payment already has a virtual account
        if ($payment->va_number && $payment->payment_url && !$payment->isExpired()) {
            Log::info('Payment already has VA', [
                'payment_id' => $payment->id,
                'va_number' => $payment->va_number,
            ]);
            return redirect()->route('payments.waiting', $payment);
        }

        $expiredHours = (int) config('services.payment.expired_hours', 24);

        try {
            // Use unique external_id to avoid duplicates
            $externalId = 'PAY-' . $payment->id . '-' . time();

            Log::info('Initiating payment', [
                'payment_id' => $payment->id,
                'external_id' => $externalId,
                'amount' => $payment->amount,
            ]);

            // Call Payment Gateway API to create virtual account
            // Ensure base_url ends with /api/v1, then append the endpoint
            $baseUrl = rtrim(config('services.payment.base_url'), '/');
            if (!str_ends_with($baseUrl, '/api/v1')) {
                $baseUrl .= '/api/v1';
            }
            $apiUrl = $baseUrl . '/virtual-account/create';
            
            Log::info('Calling payment API', [
                'url' => $apiUrl,
                'api_key' => config('services.payment.api_key'),
            ]);
            
            $response = Http::withHeaders([
                'X-API-Key' => config('services.payment.api_key'),
                'Accept' => 'application/json',
            ])->post($apiUrl, [
                'external_id' => $externalId,
                'amount' => $payment->amount,
                'customer_name' => auth()->user()->name,
                'customer_email' => auth()->user()->email,
                'customer_phone' => auth()->user()->phone ?? '081234567890',
                'description' => 'Pembayaran untuk Appointment #' . $payment->id,
                'expired_duration' => $expiredHours,
                'redirect_url' => route('payments.success', $payment->id),
                'metadata' => [
                    'payment_id' => $payment->id,
                    'user_id' => auth()->id(),
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $data = $responseData['data'] ?? [];

                $payment->update([
                    'status' => 'pending',
                    'va_number' => $data['va_number'] ?? null,
                    'payment_url' => $data['payment_url'] ?? null,
                    'expired_at' => isset($data['expired_at']) ? \Carbon\Carbon::parse($data['expired_at']) : now()->addHours($expiredHours),
                ]);

                Log::info('Virtual account created successfully', [
                    'payment_id' => $payment->id,
                    'va_number' => $payment->va_number,
                ]);

                return redirect()->route('payments.waiting', $payment);
            } else {
                $errorMessage = 'Gagal membuat pembayaran.';
                
                // Try to get detailed error message
                $responseBody = $response->body();
                $responseData = $response->json();
                
                if (isset($responseData['message'])) {
                    $errorMessage .= ' ' . $responseData['message'];
                }
                
                Log::error('Failed to create virtual account', [
                    'payment_id' => $payment->id,
                    'status' => $response->status(),
                    'response' => $responseBody,
                    'api_url' => $apiUrl,
                ]);
                
                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Payment exception', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


}
