<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Detail & Jadwal Dokter') }}
    </x-slot>

    <div class="space-y-8">

        {{-- CARD PROFIL DOKTER (Sama seperti sebelumnya) --}}
        <div class="bg-white rounded-xl p-6 card-shadow border-t-4 border-primary-blue">
            <div class="flex items-start space-x-6">
                <div class="shrink-0">
                    <img src="https://i.pravatar.cc/150?img={{ $doctor->id }}" alt="{{ $doctor->user->name }}" class="w-24 h-24 object-cover rounded-full border-4 border-gray-100">
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $doctor->user->name }}</h3>
                    <p class="text-lg font-semibold text-primary-blue">{{ $doctor->specialty }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $doctor->bio }}</p>
                    <p class="text-xs text-gray-500 mt-1">Departemen: {{ $doctor->hospitalDepartment->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- JADWAL PRAKTIK MINGGUAN (Tampilan Baru - Card per Hari) --}}
        <div class="bg-white rounded-xl p-6 card-shadow">
            <h4 class="text-xl font-bold mb-6 text-gray-800 border-b pb-3">Jadwal Praktik Tersedia</h4>

            @if(empty($schedules) || $schedules->isEmpty())
                <p class="text-center text-red-500 font-semibold py-4">⚠️ Dokter ini belum memiliki jadwal praktik.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {{-- Loop berdasarkan Hari (Key dari groupBy) --}}
                    @foreach ($schedules as $day => $daySchedules)
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <h5 class="font-bold text-lg text-primary-blue mb-3 text-center">{{ $day }}</h5>
                            <div class="space-y-2">
                                {{-- Loop Jam Praktik untuk Hari Ini --}}
                                @foreach ($daySchedules as $schedule)
                                    <div class="bg-blue-50 border border-primary-blue/30 rounded-md p-3 text-center transition hover:bg-blue-100">
                                        <p class="font-semibold text-gray-800 text-sm">
                                            {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">Kuota: {{ $schedule->max_patients }} Pasien</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- AREA FORM PEMESANAN (Sama, tapi dengan penyesuaian label) --}}
        <div class="bg-white rounded-xl p-6 card-shadow">

            <h4 class="text-xl font-bold mb-6 text-gray-800 border-b pb-3">Pesan Janji Temu</h4>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-300 rounded-lg text-red-700">
                    <p>Gagal membuat janji temu. Mohon perbaiki kesalahan:</p>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('patient.appointments.book') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                {{-- 1. Pilih Tanggal --}}
                <div>
                    <label for="appointment_date" class="block font-medium text-gray-800 mb-2">Pilih Tanggal Kunjungan</label>
                    <input type="date" id="appointment_date" name="appointment_date" required min="{{ now()->format('Y-m-d') }}"
                           class="w-full border-gray-300 rounded-xl shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue">
                    <small class="text-gray-500 mt-2 block">Pilih tanggal untuk mengaktifkan pilihan jam.</small>
                </div>

                {{-- 2. Pilih Jam (Dropdown disaring oleh JS) --}}
                <div>
                    <label for="schedule_id" class="block font-medium text-gray-800 mb-2">Pilih Jam Sesuai Jadwal di Atas</label>
                    <select id="schedule_id" name="schedule_id" required disabled
                            class="w-full border-gray-300 rounded-xl shadow-sm bg-gray-100 text-gray-500 focus:border-primary-blue focus:ring-primary-blue disabled:bg-gray-100 disabled:text-gray-500">
                        <option value="">Pilih tanggal terlebih dahulu</option>

                        {{-- Opsi Jadwal (Semua akan disembunyikan/dinonaktifkan oleh JS saat inisialisasi) --}}
                        @php $allScheduleOptions = collect($schedules)->flatten(); @endphp
                        @foreach($allScheduleOptions as $schedule)
                            <option value="{{ $schedule->id }}"
                                    data-day="{{ $schedule->day_of_week }}"
                                    class="hidden">
                                {{ $schedule->day_of_week }}, {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-red-500 mt-2 block hidden" id="no-schedule-message">🚫 Tidak ada jadwal praktik untuk hari yang Anda pilih.</small>

                    {{-- LOKASI TAMPILAN ANTRIAN --}}
                    <div id="queue-display" class="mt-3 p-3 bg-blue-50 border border-primary-blue/50 rounded-lg hidden">
                        <p class="font-semibold text-sm text-gray-800">Antrian yang akan Anda dapatkan: <span id="next-queue-number" class="font-extrabold text-lg text-primary-blue">--</span></p>
                    </div>
                </div>

                {{-- 3. Alasan Kunjungan --}}
                <div>
                    <label for="reason" class="block font-medium text-gray-800 mb-2">Alasan Kunjungan (Opsional)</label>
                    <textarea id="reason" name="reason" rows="3" placeholder="Contoh: Sakit kepala berkepanjangan, kontrol rutin"
                              class="w-full border-gray-300 rounded-xl shadow-sm bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue"></textarea>
                </div>

                <div class="flex justify-end pt-4 space-x-3">
                    <a href="{{ route('patient.doctors.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-6 rounded-xl transition duration-150 shadow-md">
                        Kembali Mencari
                    </a>
                    <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl text-lg transition duration-150 shadow-md">
                        Konfirmasi Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script JavaScript (Sama seperti sebelumnya, tidak perlu diubah) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('appointment_date');
            const scheduleSelect = document.getElementById('schedule_id');
            const placeholderOption = scheduleSelect.querySelector('option[value=""]');
            const scheduleOptions = Array.from(scheduleSelect.querySelectorAll('option:not([value=""])'));
            const noScheduleMessage = document.getElementById('no-schedule-message');
            const queueDisplay = document.getElementById('queue-display');
            const nextQueueNumberSpan = document.getElementById('next-queue-number');

            function getDayName(dateString) {
                const date = new Date(dateString + 'T00:00:00');
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                return days[date.getDay()];
            }

            function filterSchedules() {
                const dateValue = dateInput.value;

                scheduleSelect.disabled = true;
                scheduleSelect.value = "";
                scheduleOptions.forEach(opt => opt.classList.add('hidden'));
                placeholderOption.textContent = "Pilih tanggal terlebih dahulu";
                noScheduleMessage.classList.add('hidden');
                queueDisplay.classList.add('hidden');
                scheduleSelect.classList.add('bg-gray-100', 'text-gray-500');

                if (!dateValue) return;

                scheduleSelect.classList.remove('bg-gray-100', 'text-gray-500');
                scheduleSelect.disabled = false;
                placeholderOption.textContent = "Memuat jadwal...";

                const selectedDayName = getDayName(dateValue);
                let foundValidSchedule = false;

                scheduleOptions.forEach(option => {
                    const optionDay = option.getAttribute('data-day');
                    if (optionDay === selectedDayName) {
                        option.classList.remove('hidden');
                        foundValidSchedule = true;
                    } else {
                        option.classList.add('hidden');
                    }
                });

                if (foundValidSchedule) {
                    placeholderOption.textContent = "--- Pilih Jam Praktik ---";
                    scheduleSelect.value = "";
                } else {
                    placeholderOption.textContent = "Tidak ada jadwal untuk hari " + selectedDayName;
                    scheduleSelect.disabled = true;
                    noScheduleMessage.classList.remove('hidden');
                    scheduleSelect.classList.add('bg-gray-100', 'text-gray-500');
                }
            }

            function calculateQueueStatus() {
                const scheduleId = scheduleSelect.value;
                const appointmentDate = dateInput.value;

                if (!scheduleId || !appointmentDate) {
                    queueDisplay.classList.add('hidden');
                    return;
                }

                queueDisplay.classList.remove('hidden');
                nextQueueNumberSpan.textContent = 'Menghitung...';

                fetch('{{ route('patient.appointments.calculate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        schedule_id: scheduleId,
                        appointment_date: appointmentDate
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nextQueueNumberSpan.textContent = data.queue_number;
                    } else {
                        nextQueueNumberSpan.textContent = 'Gagal';
                    }
                })
                .catch(error => {
                    console.error('Error fetching queue:', error);
                    nextQueueNumberSpan.textContent = 'Gagal';
                });
            }

            dateInput.addEventListener('change', () => {
                filterSchedules();
                calculateQueueStatus();
            });
            scheduleSelect.addEventListener('change', calculateQueueStatus);

            filterSchedules(); // Inisialisasi
        });
    </script>
</x-layouts.patient-app>
