<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Pembayaran Berhasil') }}
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl p-8 card-shadow border-t-4 border-green-600">

            <!-- Success Icon -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                    <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-3xl font-extrabold text-green-600 mb-2">Janji Temu Berhasil Dikonfirmasi!</h3>
                <p class="text-gray-600">Order ID: <span class="font-semibold">{{ $order->order_number }}</span></p>
            </div>

            <!-- Detail Konfirmasi Booking -->
            <div class="border rounded-xl p-4 mb-6 bg-gray-50">
                <h4 class="font-bold mb-3 text-lg text-primary-blue">Detail Konfirmasi</h4>

                <div class="space-y-2 text-gray-700">
                    <div class="flex justify-between text-sm border-b pb-1">
                        <span class="text-gray-600">Dokter:</span>
                        <span class="font-semibold">{{ $order->appointment->doctor->user->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm border-b pb-1">
                        <span class="text-gray-600">Tanggal Janji:</span>
                        <span>{{ $order->appointment->appointment_date->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm border-b pb-1">
                        <span class="text-gray-600">Nomor Antrian:</span>
                        <span class="font-extrabold text-xl text-red-500">{{ $order->appointment->queue_number ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="font-semibold">Total Dibayar:</span>
                        <span class="text-xl font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-green-800">
                    Terima kasih! Janji temu Anda telah aktif dan terdaftar. Silakan datang tepat waktu sesuai jadwal.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <a href="{{ route('patient.appointments.index') }}" class="flex-1 bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-center transition duration-150">
                    Lihat Janji Temu Saya
                </a>
                <a href="{{ route('patient.doctors.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-4 rounded-xl text-center transition duration-150">
                    Cari Dokter Lain
                </a>
            </div>
        </div>
    </div>
</x-layouts.patient-app>
