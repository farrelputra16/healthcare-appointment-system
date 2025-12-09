<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Janji Temu Saya') }}
    </x-slot>

    <div class="space-y-6">
        <h3 class="text-2xl font-extrabold text-gray-900 mb-6">Janji Temu Mendatang & Riwayat</h3>

        @forelse ($appointments as $appointment)
            {{-- Ambil medical record yang terhubung (jika ada) --}}
            @php
                $medicalRecord = $appointment->medicalRecord;
            @endphp

            <div class="bg-white rounded-xl p-5 card-shadow border-l-4
                @if($appointment->status == 'scheduled') border-primary-blue
                @elseif($appointment->status == 'completed' && $medicalRecord) border-green-500
                @elseif($appointment->status == 'completed' && !$medicalRecord) border-yellow-500
                @else border-red-500 @endif
            ">

                {{-- Detail Header --}}
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-extrabold text-xl text-gray-900">{{ $appointment->doctor->user->name ?? 'Dokter Tidak Ditemukan' }}</p>
                        <p class="text-sm text-primary-blue font-semibold">{{ $appointment->doctor->specialty ?? '-' }}</p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full
                        @if($appointment->status == 'scheduled') bg-blue-100 text-primary-blue
                        @elseif($appointment->status == 'completed' && $medicalRecord) bg-green-100 text-green-700
                        @elseif($appointment->status == 'completed' && !$medicalRecord) bg-yellow-100 text-yellow-700
                        @else bg-red-100 text-red-700 @endif
                    ">
                        {{ strtoupper($appointment->status) }}
                        {{-- Tambahan status jika Completed tapi belum ada record --}}
                        @if ($appointment->status == 'completed' && !$medicalRecord)
                            (Menunggu Dokumen)
                        @endif
                    </span>
                </div>

                {{-- Detail Waktu dan Antrian --}}
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

                {{-- Tombol Aksi --}}
                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end space-x-2">

                    @if ($appointment->status == 'completed' && $medicalRecord)
                        {{-- TOMBOL BARU: LIHAT MEDICAL RECORD --}}
                        <a href="{{ route('patient.medical-records.show', $appointment) }}"
                           class="text-xs bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded-lg transition duration-150 font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Lihat Catatan Medis
                        </a>
                    @elseif ($appointment->status == 'scheduled')
                        {{-- Form Batalkan Janji Temu (Contoh) --}}
                        {{-- HANYA MUNCUL JIKA STATUS MASIH SCHEDULED --}}
                        <form method="POST" action="/app/appointments/{{ $appointment->id }}/cancel">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-xs bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded-lg transition duration-150">Batalkan</button>
                        </form>
                    @endif

                    {{-- Tombol "Lihat Detail Dokter" dihapus sesuai permintaan --}}

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
