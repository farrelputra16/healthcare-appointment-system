<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Antrian Pasien — {{ ucfirst($schedule->day_of_week) }} ({{ date('d M Y') }})
        </h2>
        <p class="text-gray-600">
            Waktu Praktik: {{ date('H:i', strtotime($schedule->start_time)) }} -
            {{ date('H:i', strtotime($schedule->end_time)) }}
        </p>
    </x-slot>

    <div class="max-w-full mx-auto">
        <div class="bg-white overflow-hidden shadow-2xl sm:rounded-xl">
            <div class="p-8 text-gray-900">

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

                {{-- Tabel Antrian --}}
                <table class="min-w-full text-left border-collapse rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr class="border-b border-gray-200">
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700 text-center w-20">Antrian</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Nama Pasien & Detail</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700 text-center w-32">Status</th>
                            <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700 text-center w-64">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($appointments as $app)
                            <tr class="hover:bg-blue-50/50 transition duration-100 border-b border-gray-100">
                                {{-- No Antrian --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="text-2xl font-extrabold text-primary-blue">{{ $app->queue_number }}</span>
                                </td>

                                {{-- Detail Pasien --}}
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-900">{{ $app->patient?->user?->name ?? 'Pasien Tidak Ditemukan' }}</p>
                                    <p class="text-xs text-gray-500 italic">Alasan: {{ $app->reason ?? '-' }}</p>
                                </td>

                                {{-- Status Saat Ini --}}
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClasses = [
                                            'payment_pending' => 'bg-yellow-500',
                                            'scheduled' => 'bg-blue-600',
                                            'checked_in' => 'bg-purple-600',
                                            'completed' => 'bg-green-500',
                                            'cancelled' => 'bg-red-500',
                                        ];
                                        $currentStatus = $app->status ?? 'cancelled';
                                    @endphp
                                    <span class="{{ $statusClasses[$currentStatus] ?? 'bg-gray-500' }} text-white px-3 py-1 rounded-full text-xs font-semibold shadow">
                                        {{ ucfirst(str_replace('_', ' ', $currentStatus)) }}
                                    </span>
                                </td>

                                {{-- Aksi Dokter --}}
                                <td class="px-6 py-4 text-center space-x-2 whitespace-nowrap">

                                    {{-- Aksi 1: Menandai Sedang Diperiksa (checked_in) --}}
                                    @if ($currentStatus === 'scheduled')
                                        <form method="POST" action="{{ route('doctor.appointments.update-status', $app) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="checked_in">
                                            <button type="submit" onclick="return confirm('Tandai Pasien {{ $app->queue_number }} sebagai SEDANG DIPERIKSA?')"
                                                class="bg-purple-600 text-white px-3 py-1 rounded-md text-sm shadow hover:bg-purple-700 transition">
                                                Mulai Periksa
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Aksi 2: Menandai Selesai (completed) --}}
                                    @if ($currentStatus === 'checked_in')
                                        <form method="POST" action="{{ route('doctor.appointments.update-status', $app) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" onclick="return confirm('Tandai Pasien {{ $app->queue_number }} sebagai SELESAI? Ini akan memicu pembuatan rekam medis draft.')"
                                                class="bg-green-600 text-white px-3 py-1 rounded-md text-sm shadow hover:bg-green-700 transition">
                                                Selesai Periksa
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tautan Isi Rekam Medis (Hanya muncul jika Completed) --}}
                                    @if ($currentStatus === 'completed')
                                        @if ($app->medicalRecord)
                                            {{-- Jika Record sudah ada (draft), arahkan ke EDIT --}}
                                            <a href="{{ route('medical-records.edit', $app->medicalRecord) }}"
                                                class="bg-blue-500 text-white px-3 py-1 rounded-md text-sm shadow hover:bg-blue-600 transition">
                                                Isi/Edit Rekam Medis
                                            </a>
                                        @else
                                            {{-- Fallback: Jika completed tapi record belum terbuat/terload, arahkan ke CREATE dengan Appointment ID --}}
                                            <a href="{{ route('medical-records.create', $app) }}"
                                                class="bg-orange-500 text-white px-3 py-1 rounded-md text-sm shadow hover:bg-orange-600 transition">
                                                Buat Rekam Medis
                                            </a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-xl text-gray-600 bg-gray-50">
                                    🥳 Tidak ada antrian yang terdaftar untuk jadwal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Tombol Kembali --}}
                <div class="mt-8">
                    <a href="{{ route('doctor.my-schedule') }}"
                        class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm shadow hover:bg-gray-700 font-semibold">
                        ← Kembali ke Jadwal Saya
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
