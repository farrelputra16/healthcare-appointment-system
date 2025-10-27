<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Menunggu Pembayaran') }}
        </h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto bg-white rounded-lg shadow">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-yellow-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Menunggu Pembayaran</h3>
            <p class="text-gray-600">Silakan selesaikan pembayaran Anda</p>
        </div>

        @if ($payment->va_number)
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Virtual Account</label>
                <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-4">
                    <p class="text-2xl font-mono text-center text-gray-900 font-bold tracking-wider">{{ $payment->va_number }}</p>
                </div>
            </div>
        @endif

        @if ($payment->expired_at)
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-gray-700">
                    <span class="font-semibold">⏰ Batas Waktu:</span> 
                    <span class="text-gray-900">{{ $payment->expired_at->format('d M Y, H:i') }}</span>
                </p>
            </div>
        @endif

        <div class="mt-6 space-y-3">
            @if ($payment->payment_url)
                <a href="{{ $payment->payment_url }}" target="_blank"
                   class="w-full block text-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                    🔗 Buka Halaman Pembayaran
                </a>
            @endif
            
            <a href="{{ route('payments.index') }}"
               class="w-full block text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-semibold">
                Kembali ke Daftar Pembayaran
            </a>
        </div>
    </div>
</x-app-layout>
