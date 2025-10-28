<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Konfirmasi Pembayaran') }}
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl p-8 card-shadow border-t-4 border-primary-blue">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Detail Janji Temu & Tagihan</h3>

            {{-- Detail Janji Temu --}}
            <div class="border rounded-xl p-4 mb-6 bg-gray-50">
                <h4 class="font-semibold text-lg text-primary-blue mb-2">Dokter & Jadwal</h4>
                <div class="space-y-1 text-sm text-gray-700">
                    <p><strong>Dokter:</strong> {{ $doctor->user->name }} ({{ $doctor->specialty }})</p>
                    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse(Session::get('booking_data')['appointment_date'])->translatedFormat('l, d F Y') }}</p>
                    <p><strong>Jam:</strong> {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</p>
                    <p><strong>Antrian:</strong> <span class="font-bold text-red-500">#{{ $queueNumber }}</span> (Antrian yang akan Anda dapatkan)</p>
                </div>
            </div>

            {{-- Rincian Biaya --}}
            <div class="space-y-3 mb-6">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-lg font-semibold text-gray-700">Biaya Konsultasi:</span>
                    <span class="font-bold text-lg">Rp {{ number_format($price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between pt-2">
                    <span class="text-2xl font-extrabold text-gray-900">Total Dibayar:</span>
                    <span class="text-3xl font-extrabold text-primary-blue">Rp {{ number_format($price, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('orders.process') }}" method="POST">
                @csrf
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-center transition duration-150 shadow-md">
                        Bayar Sekarang & Konfirmasi
                    </button>
                    <a href="{{ route('patient.doctors.index') }}" class="flex-1 border border-gray-400 text-gray-700 hover:bg-gray-100 font-bold py-3 px-4 rounded-xl text-center transition duration-150">
                        Batalkan Booking
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.patient-app>
