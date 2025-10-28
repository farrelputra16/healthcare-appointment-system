<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Janji Temu Saya') }}
    </x-slot>

    <div class="space-y-6">
        <h3 class="text-2xl font-extrabold text-gray-900 mb-6">Janji Temu Mendatang & Riwayat</h3>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @forelse ($appointments as $appointment)
            <div class="bg-white rounded-xl p-5 card-shadow border-l-4
                @if($appointment->status == 'scheduled') border-primary-blue
                @elseif($appointment->status == 'completed') border-green-500
                @else border-red-500 @endif
            ">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-extrabold text-xl text-gray-900">{{ $appointment->doctor->user->name ?? 'Dokter Tidak Ditemukan' }}</p>
                        <p class="text-sm text-primary-blue font-semibold">{{ $appointment->doctor->specialty ?? '-' }}</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full
                        @if($appointment->status == 'scheduled') bg-blue-100 text-primary-blue
                        @elseif($appointment->status == 'completed') bg-green-100 text-green-700
                        @else bg-red-100 text-red-700 @endif
                    ">
                        {{ strtoupper($appointment->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-y-2 text-sm text-gray-700">
                    <div>
                        <strong class="text-gray-500">Tanggal:</strong>
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('d F Y') }}
                    </div>
                    <div>
                        <strong class="text-gray-500">Waktu:</strong>
                        {{ substr($appointment->schedule->start_time ?? '-', 0, 5) }} - {{ substr($appointment->schedule->end_time ?? '-', 0, 5) }}
                    </div>
                    <div>
                        <strong class="text-gray-500">Nomor Antrian:</strong>
                        <span class="font-extrabold text-lg text-red-500">{{ $appointment->queue_number }}</span>
                    </div>
                    <div>
                        <strong class="text-gray-500">Alasan:</strong>
                        {{ $appointment->reason ?? 'Tidak ada' }}
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-sm text-gray-700">
                        <span class="text-gray-500">Status Pembayaran:</span>
                        @php
                            $paymentStatus = $appointment->payment_status ?? 'unpaid';
                            if ($appointment->status === 'payment_pending') {
                                $paymentStatus = 'pending';
                            } elseif ($appointment->status === 'scheduled') {
                                // If scheduled, check if there's an order and its status
                                if ($appointment->order) {
                                    $paymentStatus = $appointment->order->payment_status;
                                } else {
                                    $paymentStatus = 'paid'; // If no order but scheduled, assume paid
                                }
                            } elseif ($appointment->status === 'cancelled') {
                                $paymentStatus = 'cancelled';
                            }
                        @endphp
                        <span class="font-semibold {{ $paymentStatus === 'paid' ? 'text-green-600' : ($paymentStatus === 'pending' ? 'text-yellow-600' : ($paymentStatus === 'cancelled' ? 'text-gray-600' : 'text-red-600')) }}">{{ strtoupper($paymentStatus) }}</span>
                    </div>
                    <div class="text-sm text-gray-700">
                        <span class="text-gray-500">Jumlah Dibayar:</span>
                        @php
                            $amount = $appointment->order->total_amount ?? config('services.payment.consultation_fee');
                        @endphp
                        <span class="font-extrabold">Rp {{ number_format($amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-end space-x-2">
                        @php $order = $appointment->order; @endphp
                        @if($order)
                            @if($order->isPending())
                                @if($order->payment_url)
                                    <a href="{{ $order->payment_url }}" target="_blank" class="text-xs bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg transition duration-150">Bayar</a>
                                @else
                                    <a href="{{ route('orders.pay', $order) }}" class="text-xs bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-lg transition duration-150">Bayar</a>
                                @endif
                            @elseif($order->isPaid())
                                <span class="text-xs text-green-700 font-semibold">Sudah dibayar</span>
                            @endif
                        @endif
                        @if ($appointment->status == 'scheduled' || $appointment->status == 'payment_pending')
                        <form method="POST" action="{{ route('patient.appointments.cancel', $appointment) }}">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg transition duration-150">Batalkan</button>
                        </form>
                        @endif
                        <a href="{{ route('patient.doctors.schedule', $appointment->doctor_id) }}" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 py-1 px-3 rounded-lg transition duration-150">Lihat Detail Dokter</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-6 bg-yellow-100 border border-yellow-300 rounded-lg text-yellow-800 text-center">
                <p class="font-semibold">Anda belum memiliki janji temu yang terdaftar.</p>
                <a href="{{ route('patient.doctors.index') }}" class="text-primary-blue hover:underline mt-2 inline-block font-semibold">Cari Dokter Sekarang</a>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    </div>
</x-layouts.patient-app>
