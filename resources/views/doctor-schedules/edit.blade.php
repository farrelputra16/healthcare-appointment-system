<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Edit Jadwal Dokter') }}
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

            <form method="POST" action="{{ route('doctor-schedules.update', $doctorSchedule->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Pilih Dokter</label>
                    <select name="doctor_id" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $doctorSchedule->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name ?? '-' }} - {{ $doctor->hospitalDepartment->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Hari</label>
                    <select name="day_of_week" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Hari</option>
                        <option value="Senin" {{ old('day_of_week', $doctorSchedule->day_of_week) == 'Senin' ? 'selected' : '' }}>Senin</option>
                        <option value="Selasa" {{ old('day_of_week', $doctorSchedule->day_of_week) == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="Rabu" {{ old('day_of_week', $doctorSchedule->day_of_week) == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="Kamis" {{ old('day_of_week', $doctorSchedule->day_of_week) == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="Jumat" {{ old('day_of_week', $doctorSchedule->day_of_week) == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="Sabtu" {{ old('day_of_week', $doctorSchedule->day_of_week) == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                        <option value="Minggu" {{ old('day_of_week', $doctorSchedule->day_of_week) == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                    </select>
                </div>

                <div class="mb-4 grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Waktu Mulai</label>
                        <input type="time" name="start_time" value="{{ old('start_time', $doctorSchedule->start_time) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                    </div>

                    <div>
                        <label class="block text-gray-800 font-medium mb-2">Waktu Berakhir</label>
                        <input type="time" name="end_time" value="{{ old('end_time', $doctorSchedule->end_time) }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-800 font-medium mb-2">Maksimum Pasien</label>
                    <input type="number" name="max_patients" value="{{ old('max_patients', $doctorSchedule->max_patients) }}" min="1"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('doctor-schedules.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:underline transition duration-150">Batal</a>
                    <button type="submit"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                        Perbarui Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

