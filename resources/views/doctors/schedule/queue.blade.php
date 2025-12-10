<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Antrian Pasien: ') . ucfirst($schedule->day_of_week) }}
        </h2>
        <p class="text-gray-600">Waktu Praktik: {{ date('H:i', strtotime($schedule->start_time)) }} - {{ date('H:i', strtotime($schedule->end_time)) }}</p>
    </x-slot>

    <div class="space-y-6">

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg font-semibold border border-green-200">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg font-semibold border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($appointments as $app)
                @php
                    $currentStatus = $app->status ?? 'cancelled';
                    $medicalRecord = $app->medicalRecord;

                    $cardColor = [
                        'scheduled' => 'border-primary-blue/70 bg-blue-50',
                        'checked_in' => 'border-purple-600/70 bg-purple-50',
                        'completed' => 'border-green-500/70 bg-green-50',
                        'cancelled' => 'border-gray-500/70 bg-gray-50',
                        'payment_pending' => 'border-yellow-500/70 bg-yellow-50',
                    ][$currentStatus] ?? 'border-gray-300 bg-white';

                @endphp

                <div class="p-6 rounded-xl shadow-md border-l-4 {{ $cardColor }}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-2xl font-extrabold text-gray-900">
                            Antrian #{{ $app->queue_number }}
                        </h4>
                        <span class="text-xs font-bold px-3 py-1 rounded-full
                            @if($currentStatus === 'checked_in') bg-purple-600 text-white
                            @elseif($currentStatus === 'scheduled') bg-primary-blue text-white
                            @elseif($currentStatus === 'completed') bg-green-600 text-white
                            @else bg-gray-500 text-white @endif">
                            {{ ucfirst(str_replace('_', ' ', $currentStatus)) }}
                        </span>
                    </div>

                    <p class="text-lg font-bold text-gray-800">{{ $app->patient?->user?->name ?? 'Pasien Tidak Ditemukan' }}</p>
                    <p class="text-sm text-gray-600 italic mt-1">Alasan: {{ $app->reason ?? '-' }}</p>

                    <div class="mt-4 pt-3 border-t border-gray-200 flex justify-between items-center">

                        {{-- Status Dokumentasi --}}
                        <div class="text-xs font-medium">
                            @if ($currentStatus === 'completed')
                                @if ($medicalRecord)
                                    <span class="text-green-600">Dokumen tersedia.</span>
                                @else
                                    <span class="text-red-500 font-semibold">Perlu didokumentasikan.</span>
                                @endif
                            @endif
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="space-x-2 whitespace-nowrap">

                            @if ($currentStatus === 'scheduled')
                                {{-- MULAI PERIKSA --}}
                                <form method="POST" action="{{ route('doctor.appointments.update-status', $app) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="checked_in">
                                    <button type="submit" class="bg-purple-600 text-white px-3 py-1 rounded-md text-sm shadow hover:bg-purple-700 transition font-semibold">
                                        Mulai Periksa
                                    </button>
                                </form>
                            @endif

                            @if ($currentStatus === 'checked_in')
                                {{-- SELESAI PERIKSA --}}
                                <form method="POST" action="{{ route('doctor.appointments.update-status', $app) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded-md text-sm shadow hover:bg-green-700 transition font-semibold">
                                        Selesai Periksa
                                    </button>
                                </form>
                            @endif

                            @if ($currentStatus === 'completed')
                                {{-- ISI/EDIT REKAM MEDIS --}}
                                @if ($medicalRecord)
                                    <a href="{{ route('medical-records.edit', $medicalRecord) }}"
                                        class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm shadow hover:bg-blue-700 transition font-semibold">
                                        Isi/Edit MR
                                    </a>
                                @else
                                    <a href="{{ route('medical-records.create', $app) }}"
                                        class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm shadow hover:bg-orange-600 transition font-semibold">
                                        Buat MR
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 p-8 text-center bg-gray-50 border border-gray-300 rounded-lg">
                    <p class="text-xl text-gray-600">🥳 Tidak ada antrian yang terdaftar untuk jadwal ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Tombol Kembali --}}
        <div class="mt-8">
            <a href="{{ route('doctor.my-schedule') }}"
                class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm shadow hover:bg-gray-700 font-semibold">
                ← Kembali ke Jadwal Saya
            </a>
        </div>

    </div>
</x-app-layout>
