<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

// Webhook Route (no CSRF protection)
Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment');
