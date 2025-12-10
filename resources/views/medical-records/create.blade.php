<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Tambah Rekam Medis Baru') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl p-8">

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

            <form method="POST" action="{{ route('medical-records.store') }}">
                @csrf

                {{-- Flag untuk menentukan apakah mode pre-fill aktif dan apakah Admin --}}
                @php
                    $isPreFilled = isset($preselectedPatientId) && isset($preselectedAppointmentId);
                    $isAdmin = Auth::user()->isAdmin();
                @endphp

                @if ($isPreFilled)
                    <div class="mb-6 p-4 bg-blue-50 border border-primary-blue/30 rounded-lg text-sm text-gray-700 font-medium">
                        Anda sedang mengisi rekam medis untuk Janji Temu yang baru selesai. Pasien dan Janji Temu dikunci otomatis.
                    </div>
                @endif

                {{-- 0. PILIH DOKTER (Hanya untuk Admin di mode normal) --}}
                @if ($isAdmin && !$isPreFilled)
                    <div class="mb-4">
                        <label class="block text-gray-800 font-medium mb-2">Dokter <span class="text-red-500">*</span></label>
                        <select name="doctor_id"
                            class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                            <option value="">Pilih Dokter</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->user->name ?? 'N/A' }} ({{ $doctor->specialty ?? 'Umum' }})
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                {{-- 1. PILIH PASIEN --}}
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Pasien <span class="text-red-500">*</span></label>
                    <select name="patient_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-blue focus:ring-primary-blue {{ $isPreFilled ? 'bg-gray-100 disabled:opacity-75' : 'bg-white' }}"
                        required
                        {{ $isPreFilled ? 'disabled' : '' }}
                    >
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}"
                                {{ ($isPreFilled && $preselectedPatientId == $patient->id) ? 'selected' : (old('patient_id') == $patient->id ? 'selected' : '') }}>
                                {{ $patient->user->name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    {{-- Hidden field untuk mengirim ID Pasien jika disabled --}}
                    @if($isPreFilled)
                        <input type="hidden" name="patient_id" value="{{ $preselectedPatientId }}">
                    @endif
                </div>

                {{-- 2. PILIH JANJI TEMU --}}
                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Janji Temu <span class="text-red-500">*</span></label>
                    <select name="appointment_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-primary-blue focus:ring-primary-blue {{ $isPreFilled ? 'bg-gray-100 disabled:opacity-75' : 'bg-white' }}"
                        required
                        {{ $isPreFilled ? 'disabled' : '' }}
                    >
                        <option value="">Pilih Janji Temu</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}"
                                {{ ($isPreFilled && $preselectedAppointmentId == $appointment->id) ? 'selected' : (old('appointment_id') == $appointment->id ? 'selected' : '') }}>
                                {{ $appointment->patient->user->name ?? 'N/A' }} dengan Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}
                                ({{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') : 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    {{-- Hidden field untuk mengirim ID Appointment jika disabled --}}
                    @if($isPreFilled)
                        <input type="hidden" name="appointment_id" value="{{ $preselectedAppointmentId }}">
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Diagnosis <span class="text-red-500">*</span></label>
                    <input type="text" name="diagnosis" value="{{ old('diagnosis') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue"
                        placeholder="Masukkan diagnosis" required>
                    @error('diagnosis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-800 font-medium mb-2">Catatan</label>
                    <textarea name="notes" rows="5"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue"
                        placeholder="Masukkan catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('medical-records.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:underline transition duration-150">Batal</a>
                    <button type="submit"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                        Simpan Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
