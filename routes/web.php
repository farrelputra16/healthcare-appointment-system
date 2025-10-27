<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class)
        ->middleware('role:admin');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Payment routes (protected)
    Route::resource('payments', PaymentController::class);
    Route::post('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::get('/payments/{payment}/waiting', [PaymentController::class, 'waiting'])->name('payments.waiting');
    Route::get('/payments/{payment}/success', [PaymentController::class, 'success'])->name('payments.success');
});

// Webhook route (public, CSRF exempted in bootstrap/app.php)
Route::post('/webhook/payment', [PaymentController::class, 'webhook'])->name('payments.webhook');



require __DIR__.'/auth.php';
