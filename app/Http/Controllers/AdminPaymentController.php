<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index()
    {
        // Get all orders with their related appointment, patient, and doctor information
        $payments = Order::with([
            'appointment.patient.user',
            'appointment.doctor.user',
            'user'
        ])
        ->whereHas('appointment') // Only get orders that have appointments
        ->latest()
        ->get();

        return view('admin.payments.index', compact('payments'));
    }
}
