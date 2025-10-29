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

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Pasien <span class="text-red-500">*</span></label>
                    <select name="patient_id" 
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', $medicalRecord->patient_id) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->user->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Dokter <span class="text-red-500">*</span></label>
                    <select name="doctor_id" 
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $medicalRecord->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name ?? 'N/A' }} - {{ $doctor->specialty ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Janji Temu <span class="text-red-500">*</span></label>
                    <select name="appointment_id" 
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Janji Temu</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ old('appointment_id', $medicalRecord->appointment_id) == $appointment->id ? 'selected' : '' }}>
                                {{ $appointment->patient->user->name ?? 'N/A' }} dengan Dr. {{ $appointment->doctor->user->name ?? 'N/A' }} 
                                ({{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') : 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Diagnosis <span class="text-red-500">*</span></label>
                    <input type="text" name="diagnosis" value="{{ old('diagnosis', $medicalRecord->diagnosis) }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" 
                        placeholder="Masukkan diagnosis" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-800 font-medium mb-2">Catatan</label>
                    <textarea name="notes" rows="5"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue"
                        placeholder="Masukkan catatan tambahan (opsional)">{{ old('notes', $medicalRecord->notes) }}</textarea>
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
