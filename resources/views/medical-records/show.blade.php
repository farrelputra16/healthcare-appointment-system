<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Detail Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8">

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">
                    Informasi Rekam Medis
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Pasien:</strong>
                        <div class="text-lg text-gray-900">{{ $medicalRecord->patient->user->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Dokter:</strong>
                        <div class="text-lg text-gray-900">{{ $medicalRecord->doctor->user->name ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Janji Temu:</strong>
                        <div class="text-lg text-gray-900">
                            @if($medicalRecord->appointment)
                                {{ $medicalRecord->appointment->appointment_date ? \Carbon\Carbon::parse($medicalRecord->appointment->appointment_date)->format('d M Y') : 'N/A' }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Diagnosis:</strong>
                        <div class="text-lg text-gray-900">{{ $medicalRecord->diagnosis ?? '-' }}</div>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="block text-sm font-medium text-gray-500">Catatan:</strong>
                        <div class="text-lg text-gray-900 mt-2 p-3 bg-gray-50 rounded-lg">
                            {{ $medicalRecord->notes ?? 'Tidak ada catatan' }}
                        </div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Dibuat Pada:</strong>
                        <div class="text-lg text-gray-900">{{ $medicalRecord->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <strong class="block text-sm font-medium text-gray-500">Diperbarui Pada:</strong>
                        <div class="text-lg text-gray-900">{{ $medicalRecord->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('medical-records.index') }}"
                    class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 font-bold transition duration-150">
                    Kembali
                </a>
                <a href="{{ route('medical-records.edit', $medicalRecord->id) }}"
                    class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                    Edit Rekam Medis
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
