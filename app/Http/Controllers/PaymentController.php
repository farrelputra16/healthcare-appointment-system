<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('appointment')->latest()->get();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $appointments = Appointment::all();
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
        $appointments = Appointment::all();
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
}
