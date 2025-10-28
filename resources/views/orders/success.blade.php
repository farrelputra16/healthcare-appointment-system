<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Pembayaran Berhasil') }}
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl p-8 card-shadow border-t-4 border-green-500">
            
            <!-- Success Icon -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">Pembayaran Berhasil!</h3>
                <p class="text-gray-600">Order ID: <span class="font-semibold">{{ $order->order_number }}</span></p>
            </div>

            <!-- Payment Details -->
            <div class="border rounded-xl p-4 mb-6 bg-gray-50">
                <h4 class="font-semibold text-lg text-green-600 mb-3">Detail Pembayaran</h4>
                <div class="space-y-2 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>Virtual Account:</span>
                        <span class="font-mono font-semibold">{{ $order->va_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Jumlah Dibayar:</span>
                        <span class="font-bold text-green-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Tanggal Pembayaran:</span>
                        <span class="font-semibold">{{ $order->paid_at->format('d M Y H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status:</span>
                        <span class="font-bold text-green-600 uppercase">{{ $order->payment_status }}</span>
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            @if($order->appointment)
                <div class="border rounded-xl p-4 mb-6 bg-blue-50">
                    <h4 class="font-semibold text-lg text-blue-600 mb-3">Detail Janji Temu</h4>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex justify-between">
                            <span>Dokter:</span>
                            <span class="font-semibold">{{ $order->appointment->doctor->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Spesialisasi:</span>
                            <span class="font-semibold">{{ $order->appointment->doctor->specialty ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tanggal Janji Temu:</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($order->appointment->appointment_date)->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jam:</span>
                            <span class="font-semibold">
                                {{ substr($order->appointment->schedule->start_time ?? '-', 0, 5) }} - 
                                {{ substr($order->appointment->schedule->end_time ?? '-', 0, 5) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Nomor Antrian:</span>
                            <span class="font-bold text-red-500 text-lg">#{{ $order->appointment->queue_number }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Important Notes -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h5 class="font-semibold text-yellow-800 mb-2">📋 Informasi Penting:</h5>
                <ul class="text-sm text-yellow-700 space-y-1">
                    <li>• Janji temu Anda telah dikonfirmasi dan tercatat dalam sistem</li>
                    <li>• Silakan datang sesuai jadwal yang telah ditentukan</li>
                    <li>• Bawa identitas diri dan nomor antrian untuk verifikasi</li>
                    <li>• Jika ada perubahan jadwal, silakan hubungi klinik</li>
                </ul>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <a href="{{ route('patient.appointments.index') }}" class="flex-1 bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-center transition duration-150">
                    Lihat Janji Temu Saya
                </a>
                <a href="{{ route('patient.doctors.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-4 rounded-xl text-center transition duration-150">
                    Buat Janji Temu Lain
                </a>
            </div>

            <!-- Receipt Download -->
            <div class="mt-6 text-center">
                <button onclick="window.print()" class="text-sm text-gray-600 hover:text-gray-800 underline">
                    📄 Cetak Bukti Pembayaran
                </button>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .card-shadow { box-shadow: none !important; }
        }
    </style>
</x-layouts.patient-app>
