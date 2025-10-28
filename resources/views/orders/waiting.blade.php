<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Menunggu Pembayaran') }}
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl p-8 card-shadow border-t-4 border-yellow-500">

            <!-- Order Info -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">Pembayaran Menunggu Konfirmasi</h3>
                <p class="text-gray-600">Order ID: <span class="font-semibold">{{ $order->order_number }}</span></p>
            </div>

            <!-- VA Info -->
            <div class="border rounded-xl p-4 mb-6 bg-gray-50">
                <p class="text-sm text-gray-600 mb-2">Nomor Virtual Account:</p>
                <div class="flex items-center gap-2">
                    <input type="text" value="{{ $order->va_number }}" id="va-number" readonly
                        class="flex-1 font-mono text-xl font-semibold bg-white border-gray-300 rounded-xl px-3 py-2 shadow-sm">
                    <button onclick="copyVA()" class="bg-primary-blue hover:bg-blue-700 text-white px-4 py-2 rounded-xl transition duration-150">
                        Copy VA
                    </button>
                </div>
            </div>

            <!-- Total Info -->
            <div class="border-t pt-4 flex justify-between items-center mb-6">
                <span class="font-semibold text-gray-700">Total Tagihan:</span>
                <span class="text-2xl font-extrabold text-primary-blue">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>

            <!-- Expired Info -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <span class="font-semibold">Batas Waktu Pembayaran:</span>
                    {{ $order->expired_at->format('d M Y H:i') }} WIB
                </p>
                <p class="text-xs text-yellow-700 mt-1">Jika melewati batas waktu, janji temu akan dibatalkan.</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <a href="{{ $order->payment_url }}" target="_blank" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl text-center transition duration-150">
                    Buka Panduan Pembayaran
                </a>
                <a href="{{ route('patient.doctors.index') }}" class="flex-1 bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-4 rounded-xl text-center transition duration-150">
                    Kembali ke Pencarian
                </a>
            </div>

            <!-- Auto Refresh Status -->
            <p class="text-center text-sm text-gray-500 mt-4">
                Halaman akan otomatis refresh setiap 10 detik
            </p>
        </div>
    </div>

    <script>
        function copyVA() {
            const vaInput = document.getElementById('va-number');
            vaInput.select();
            document.execCommand('copy');
            alert('Nomor VA berhasil dicopy!');
        }

        // Auto check payment status every 10 seconds
        setInterval(function() {
            fetch('{{ route("orders.check-va-status", $order) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'paid') {
                        window.location.href = '{{ route("orders.success", $order) }}';
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                });
        }, 10000);
    </script>
</x-layouts.patient-app>
