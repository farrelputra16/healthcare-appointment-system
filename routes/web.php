<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PatientAppController;
use App\Http\Controllers\AppointmentPaymentController; // Tambahkan Controller Pembayaran
use App\Http\Controllers\WebhookController;
use App\Providers\RouteServiceProvider;

Route::get('/', function () {
    return view('welcome');
});

// Route Redirect Utama
Route::get('/dashboard', function () {
    return redirect(RouteServiceProvider::getHomeRoute());
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD BERDASARKAN PERAN
    Route::get('/admin/dashboard', function () {
        return view('dashboard');
    })->middleware('role:admin')->name('admin.dashboard');

    Route::get('/doctor/dashboard', function () {
        return view('dashboard');
    })->middleware('role:doctor')->name('doctor.dashboard');


    // USER MANAGEMENT
    Route::resource('users', UserController::class)
        ->middleware('role:admin');

    // ----------------------------------------------------
    // APLIKASI PASIEN & FLOW BOOKING
    // ----------------------------------------------------

    // Halaman Utama Pencarian Dokter (Home untuk Pasien)
    Route::get('/app/doctors', [PatientAppController::class, 'index'])->name('patient.doctors.index');
    Route::get('/app/doctors/{doctor}/schedule', [PatientAppController::class, 'showSchedule'])->name('patient.doctors.schedule');

    // Flow Booking (Route POST dari form schedule)
    Route::post('/app/appointments/confirm', [PatientAppController::class, 'bookAppointment'])->name('patient.appointments.book');

    // FIX: Route Konfirmasi (GET, menerima query params dari redirect bookAppointment)
    Route::get('/orders/confirm', [AppointmentPaymentController::class, 'confirm'])->name('orders.confirm');

    // Route Pembayaran
    Route::post('/orders/process', [AppointmentPaymentController::class, 'process'])->name('orders.process');
    Route::get('/orders/{order}/waiting', [AppointmentPaymentController::class, 'waiting'])->name('orders.waiting');
    Route::get('/orders/{order}/check-status', [AppointmentPaymentController::class, 'checkStatus'])->name('orders.check-status');
    Route::get('/orders/{order}/success', [AppointmentPaymentController::class, 'success'])->name('orders.success');

    // My Appointments
    Route::get('/app/my-appointments', [PatientAppController::class, 'myAppointments'])->name('patient.appointments.index');
    Route::post('/app/appointments/calculate-queue', [PatientAppController::class, 'calculateQueue'])->name('patient.appointments.calculate');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Webhook Route (no auth middleware)
Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment');

require __DIR__.'/auth.php';
