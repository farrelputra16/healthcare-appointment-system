<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Pembayaran Berhasil') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto bg-white rounded-lg shadow">
        <div class="text-center mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-bold text-green-700 mb-2">🎉 Pembayaran Berhasil!</h3>
            <p class="text-gray-600">Terima kasih atas pembayaran Anda</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Payment ID:</span>
                    <span class="font-semibold text-gray-900">#{{ $payment->id }}</span>
                </div>
                @if ($payment->appointment)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Appointment:</span>
                        <span class="font-semibold text-gray-900">#{{ $payment->appointment_id }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-600">Jumlah:</span>
                    <span class="font-semibold text-green-700 text-lg">Rp {{ number_format($payment->amount, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Metode:</span>
                    <span class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</span>
                </div>
                @if ($payment->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Bayar:</span>
                        <span class="font-semibold text-gray-900">{{ $payment->paid_at->format('d M Y, H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('payments.index') }}"
               class="w-full block text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                Kembali ke Daftar Pembayaran
            </a>
        </div>
    </div>
</x-app-layout>
