<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PatientAppController;
use App\Http\Controllers\AppointmentPaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AdminPaymentController;
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

    // MANAJEMEN APLIKASI UTAMA
    Route::resource('users', UserController::class)
        ->middleware('role:admin');
    
    // MANAJEMEN JADWAL DOKTER
    Route::resource('doctor-schedules', DoctorScheduleController::class)
        ->middleware('role:admin');
    
    // MANAJEMEN JANJI TEMU
    Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index')->middleware('role:admin');
    Route::get('appointments/schedule/{schedule}', [AppointmentController::class, 'showAppointments'])->name('appointments.schedule')->middleware('role:admin');
    Route::get('appointments/create', [AppointmentController::class, 'create'])->name('appointments.create')->middleware('role:admin');
    Route::post('appointments', [AppointmentController::class, 'store'])->name('appointments.store')->middleware('role:admin');
    Route::get('appointments/{appointment}', [AppointmentController::class, 'show'])->name('appointments.show')->middleware('role:admin');
    Route::post('appointments/{appointment}/update-queue', [AppointmentController::class, 'updateQueue'])->name('appointments.update-queue')->middleware('role:admin');
    Route::post('appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus'])->name('appointments.update-status')->middleware('role:admin');
    Route::delete('appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy')->middleware('role:admin');
    
    // ADMIN PAYMENTS
    Route::get('/admin/payments', [AdminPaymentController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.payments.index');
});

// API routes for getting schedules
Route::get('/api/doctors/{doctor}/schedules', function ($doctor) {
    $schedules = \App\Models\DoctorSchedule::where('doctor_id', $doctor)->get();
    return response()->json($schedules);
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    // ----------------------------------------------------
    // APLIKASI PASIEN
    // ----------------------------------------------------

    // Halaman Utama Pencarian Dokter (Home untuk Pasien)
    Route::get('/app/doctors', [PatientAppController::class, 'index'])->name('patient.doctors.index');

    // Melihat Profil Detail Dokter dan Jadwal
    Route::get('/app/doctors/{doctor}/schedule', [PatientAppController::class, 'showSchedule'])->name('patient.doctors.schedule');

    // --- BARU: Route untuk halaman detail departemen ---
    Route::get('/app/doctors/department/{department}', [PatientAppController::class, 'showDepartment'])->name('patient.doctors.department');

    // Proses Pembuatan Janji Temu (Flow Pembayaran)
    Route::post('/app/appointments', [PatientAppController::class, 'bookAppointment'])->name('patient.appointments.book');
    Route::get('/orders/confirm', [AppointmentPaymentController::class, 'confirm'])->name('orders.confirm');
    Route::post('/orders/process', [AppointmentPaymentController::class, 'process'])->name('orders.process');
    Route::get('/orders/{order}/waiting', [AppointmentPaymentController::class, 'waiting'])->name('orders.waiting');
    Route::get('/orders/{order}/check-status', [AppointmentPaymentController::class, 'checkStatus'])->name('orders.check-status');
    Route::get('/orders/{order}/success', [AppointmentPaymentController::class, 'success'])->name('orders.success');

    // Janji Temu Saya & Kalkulasi Antrian
    Route::get('/app/my-appointments', [PatientAppController::class, 'myAppointments'])->name('patient.appointments.index');
    Route::post('/app/appointments/calculate-queue', [PatientAppController::class, 'calculateQueue'])->name('patient.appointments.calculate');
});

Route::resource('payments', PaymentController::class)->except(['create', 'store', 'show', 'edit', 'update', 'destroy']); // Dikosongkan karena tidak dipakai

// Webhook Route (tanpa auth middleware)
Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment');

require __DIR__ . '/auth.php';
