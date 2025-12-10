<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Rekam Medis') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8">

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('medical-records.update', $medicalRecord->id) }}">
                @csrf
                @method('PUT')

                {{-- 1. PASIEN (Dikunci, Nilai dari $medicalRecord) --}}
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Pasien</label>
                    <input type="text"
                        value="{{ $medicalRecord->patient->user->name ?? 'N/A' }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100 text-gray-700 focus:ring-0"
                        disabled>
                    {{-- Hidden input untuk memastikan ID Pasien dikirim saat PUT --}}
                    <input type="hidden" name="patient_id" value="{{ $medicalRecord->patient_id }}">
                </div>

                {{-- 2. JANJI TEMU (Dikunci, Nilai dari $medicalRecord) --}}
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Janji Temu</label>
                    <input type="text"
                        value="{{ $medicalRecord->appointment->patient->user->name ?? 'N/A' }} dengan Dr. {{ $medicalRecord->appointment->doctor->user->name ?? 'N/A' }} ({{ $medicalRecord->appointment->appointment_date->format('d M Y') }})"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-gray-100 text-gray-700 focus:ring-0"
                        disabled>
                    {{-- Hidden input untuk memastikan ID Appointment dikirim saat PUT --}}
                    <input type="hidden" name="appointment_id" value="{{ $medicalRecord->appointment_id }}">
                </div>

                {{-- 3. DIAGNOSIS --}}
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Diagnosis <span class="text-red-500">*</span></label>
                    <input type="text" name="diagnosis" value="{{ old('diagnosis', $medicalRecord->diagnosis) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue"
                        placeholder="Masukkan diagnosis" required>
                    @error('diagnosis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- 4. CATATAN --}}
                <div class="mb-6">
                    <label class="block text-gray-800 font-medium mb-2">Catatan</label>
                    <textarea name="notes" rows="5"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue"
                        placeholder="Masukkan catatan tambahan (opsional)">{{ old('notes', $medicalRecord->notes) }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('medical-records.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:underline transition duration-150">Batal</a>
                    <button type="submit"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                        Perbarui Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
