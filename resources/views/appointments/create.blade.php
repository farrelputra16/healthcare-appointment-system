<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Buat Janji Temu Baru') }}
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

            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Pilih Dokter <span class="text-red-600">*</span></label>
                    <select name="doctor_id" id="doctor_id" onchange="loadSchedules()" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Dokter</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->user->name }} - {{ $doctor->hospitalDepartment->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Jadwal Dokter <span class="text-red-600">*</span></label>
                    <select name="schedule_id" id="schedule_id" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Jadwal</option>
                        @if(isset($selectedScheduleId))
                            @php
                                $selectedSchedule = \App\Models\DoctorSchedule::find($selectedScheduleId);
                            @endphp
                            @if($selectedSchedule)
                                <option value="{{ $selectedSchedule->id }}" selected>
                                    {{ $selectedSchedule->day_of_week }} - {{ date('H:i', strtotime($selectedSchedule->start_time)) }} - {{ date('H:i', strtotime($selectedSchedule->end_time)) }}
                                </option>
                            @endif
                        @endif
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Pilih Pasien <span class="text-red-600">*</span></label>
                    <select name="patient_id" class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                        <option value="">Pilih Pasien</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->user->name ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-800 font-medium mb-2">Tanggal Janji Temu <span class="text-red-600">*</span></label>
                    <input type="date" name="appointment_date" value="{{ old('appointment_date', isset($selectedDate) ? $selectedDate : '') }}" min="{{ date('Y-m-d') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-800 font-medium mb-2">Alasan</label>
                    <textarea name="reason" rows="3" placeholder="Alasan kunjungan..."
                        class="w-full border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">{{ old('reason') }}</textarea>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('appointments.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:underline transition duration-150">Batal</a>
                    <button type="submit"
                        class="bg-primary-blue text-white px-5 py-2 rounded-lg hover:bg-blue-700 font-bold transition duration-150">
                        Buat Janji Temu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load doctor schedules via AJAX
        function loadSchedules() {
            const doctorId = document.getElementById('doctor_id').value;
            const scheduleSelect = document.getElementById('schedule_id');
            
            scheduleSelect.innerHTML = '<option value="">Loading...</option>';
            
            if (!doctorId) {
                scheduleSelect.innerHTML = '<option value="">Pilih Jadwal</option>';
                return;
            }
            
            fetch(`/api/doctors/${doctorId}/schedules`)
                .then(response => response.json())
                .then(data => {
                    scheduleSelect.innerHTML = '<option value="">Pilih Jadwal</option>';
                    data.forEach(schedule => {
                        const option = document.createElement('option');
                        option.value = schedule.id;
                        option.textContent = `${schedule.day_of_week} - ${schedule.start_time} - ${schedule.end_time}`;
                        scheduleSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    scheduleSelect.innerHTML = '<option value="">Error loading schedules</option>';
                });
        }
        
        // Load schedules on page load if doctor is already selected
        window.onload = function() {
            if (document.getElementById('doctor_id').value) {
                loadSchedules();
            }
        }
    </script>
</x-app-layout>

