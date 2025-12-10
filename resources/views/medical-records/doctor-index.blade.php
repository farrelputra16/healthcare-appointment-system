<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            Antrian Selesai (Rekam Medis)
        </h2>
        <p class="text-gray-600">Daftar janji temu yang telah selesai dan siap didokumentasikan.</p>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-2xl sm:rounded-xl">
        <div class="p-8 text-gray-900">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <table class="min-w-full text-left border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Pasien</th>
                        <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Antrian</th>
                        <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700">Status Dokumentasi</th>
                        <th class="border-b px-6 py-3 text-sm font-semibold text-gray-700 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($appointments as $app)
                        @php
                            $record = $app->medicalRecord;
                            $isDocumented = $record && !empty($record->diagnosis);
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-100 border-b border-gray-100">

                            <td class="px-6 py-4 text-sm font-medium">
                                {{ $app->appointment_date->translatedFormat('d M Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">{{ $app->patient?->user?->name ?? 'Pasien Anonim' }}</p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-extrabold text-primary-blue">{{ $app->queue_number }}</span>
                            </td>

                            <td class="px-6 py-4">
                                @if ($isDocumented)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Lengkap
                                    </span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                        Perlu Diisi
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if ($record)
                                    <a href="{{ route('medical-records.edit', $record) }}"
                                       class="bg-primary-blue hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm shadow font-semibold transition">
                                        {{ $isDocumented ? 'Edit' : 'Input Rekam Medis' }}
                                    </a>
                                @else
                                    <span class="text-red-500 text-xs">Error Data</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-600">
                                Tidak ada janji temu yang berstatus 'Selesai' saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $appointments->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
