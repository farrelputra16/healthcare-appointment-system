<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Detail & Jadwal Dokter') }}
    </x-slot>

    <div class="space-y-10">

        {{-- 🧑‍⚕️ CARD PROFIL DOKTER (Desain Premium) --}}
        <div class="bg-white rounded-2xl p-8 shadow-2xl border-l-8 border-primary-blue">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                <div class="shrink-0 text-center">
                    <img src="https://i.pravatar.cc/150?img={{ $doctor->id }}" alt="{{ $doctor->user->name }}"
                         class="w-32 h-32 object-cover rounded-full border-4 border-primary-blue/30 shadow-lg mb-2">
                    <p class="text-xs text-gray-500 mt-1 font-medium">Departemen: {{ $doctor->hospitalDepartment->name ?? '-' }}</p>
                </div>

                <div class="text-center md:text-left">
                    <h3 class="text-4xl font-extrabold text-gray-900 mb-1 leading-tight">{{ $doctor->user->name }}</h3>
                    <p class="text-xl font-bold text-primary-blue mb-3 border-b border-blue-100 pb-2 inline-block">{{ $doctor->specialty }}</p>
                    <p class="text-sm text-gray-700 mt-2 italic max-w-2xl">{{ $doctor->bio }}</p>

                    {{-- Quick Stats --}}
                    <div class="flex items-center space-x-4 mt-4 text-sm font-semibold text-gray-600 justify-center md:justify-start">
                         <div class="flex items-center bg-gray-50 p-2 rounded-lg">
                            <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>Pengalaman > 10 Tahun</span>
                        </div>
                        <div class="flex items-center bg-gray-50 p-2 rounded-lg">
                            <svg class="w-4 h-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <span>4.8 Rating</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🗓️ JADWAL PRAKTIK MINGGUAN (Tampilan Grid yang Informatif) --}}
        <div class="bg-white rounded-2xl p-8 shadow-xl">
            <h4 class="text-2xl font-extrabold mb-6 text-gray-900 border-b pb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-primary-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Ketersediaan Jadwal Praktik
            </h4>

            @if(empty($schedules) || $schedules->isEmpty())
                <p class="text-center text-red-500 font-bold py-6 bg-red-50 rounded-lg">⚠️ Dokter ini belum memiliki jadwal praktik. Tidak dapat melakukan pemesanan.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    {{-- Loop berdasarkan Hari (Key dari groupBy) --}}
                    @foreach ($schedules as $day => $daySchedules)
                        <div class="border border-primary-blue/20 rounded-xl p-5 bg-gray-50 hover:bg-white transition duration-200 shadow-sm hover:shadow-md">
                            <h5 class="font-extrabold text-xl text-primary-blue mb-4 text-center border-b pb-2">{{ $day }}</h5>
                            <div class="space-y-3">
                                {{-- Loop Jam Praktik untuk Hari Ini --}}
                                @foreach ($daySchedules as $schedule)
                                    @php
                                        // Hitung Kuota Tersisa (Max - Sudah Dipesan)
                                        $remainingQuota = $schedule->max_patients - $schedule->booked_count;
                                        $quotaStatus = $remainingQuota > 0 ? 'text-gray-600' : 'text-red-500 font-bold';
                                        $quotaText = $remainingQuota > 0 ? $remainingQuota : 'Habis';
                                    @endphp
                                    <div class="bg-blue-100/50 border border-primary-blue/30 rounded-lg p-3 text-center transition hover:bg-blue-200/70 cursor-pointer">
                                        <p class="font-bold text-gray-800 text-md">
                                            {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                        </p>
                                        <p class="text-xs mt-1 font-medium {{ $quotaStatus }}">
                                            Kuota Tersisa: **{{ $quotaText }}**
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- 📝 AREA FORM PEMESANAN (Struktur Rapi dan Jelas) --}}
        <div class="bg-white rounded-2xl p-8 shadow-2xl border-t-4 border-green-500/70">

            <h4 class="text-2xl font-extrabold mb-8 text-gray-900 border-b pb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Form Pemesanan Janji Temu
            </h4>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-300 rounded-xl text-red-700 font-semibold">
                    <p>Gagal membuat janji temu. Mohon perbaiki kesalahan yang disorot.</p>
                </div>
            @endif

            <form method="POST" action="{{ route('patient.appointments.book') }}" class="space-y-8">
                @csrf
                <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- 1. Pilih Tanggal --}}
                    <div>
                        <label for="appointment_date" class="block font-bold text-gray-800 mb-2 text-lg">1. Pilih Tanggal Kunjungan</label>
                        <input type="date" id="appointment_date" name="appointment_date" required min="{{ now()->format('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-xl shadow-md bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue py-3 px-4 transition duration-150">
                        <small class="text-gray-500 mt-2 block">Pilih tanggal di masa depan yang sesuai dengan jadwal praktik di atas.</small>
                    </div>

                    {{-- 2. Pilih Jam --}}
                    <div>
                        <label for="schedule_id" class="block font-bold text-gray-800 mb-2 text-lg">2. Pilih Jam Praktik</label>
                        <select id="schedule_id" name="schedule_id" required disabled
                                class="w-full border-gray-300 rounded-xl shadow-md focus:border-primary-blue focus:ring-primary-blue py-3 px-4 transition duration-150
                                       bg-gray-100 text-gray-500 disabled:bg-gray-100 disabled:text-gray-500">
                            <option value="">Pilih tanggal terlebih dahulu</option>

                            {{-- Opsi Jadwal (Sama, untuk filtering JS) --}}
                            @php $allScheduleOptions = collect($schedules)->flatten(); @endphp
                            @foreach($allScheduleOptions as $schedule)
                                <option value="{{ $schedule->id }}"
                                        data-day="{{ $schedule->day_of_week }}"
                                        class="hidden">
                                    {{ $schedule->day_of_week }}, {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-red-500 mt-2 block hidden font-semibold" id="no-schedule-message">🚫 Tidak ada jadwal praktik untuk hari yang Anda pilih.</small>
                    </div>
                </div>

                {{-- LOKASI TAMPILAN ANTRIAN --}}
                <div id="queue-display" class="p-4 bg-green-50 border border-green-500/50 rounded-xl hidden transition duration-300">
                    <p class="font-bold text-lg text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Nomor Antrian Anda Selanjutnya: <span id="next-queue-number" class="font-extrabold text-2xl text-green-600 ml-3">--</span>
                    </p>
                </div>

                {{-- 3. Alasan Kunjungan --}}
                <div>
                    <label for="reason" class="block font-bold text-gray-800 mb-2 text-lg">3. Alasan Kunjungan (Opsional)</label>
                    <textarea id="reason" name="reason" rows="4" placeholder="Contoh: Sakit kepala berkepanjangan, kontrol rutin, perlu rujukan"
                              class="w-full border-gray-300 rounded-xl shadow-md bg-white text-gray-900 focus:border-primary-blue focus:ring-primary-blue transition duration-150"></textarea>
                </div>

                <div class="flex justify-end pt-4 space-x-4 border-t border-gray-100">
                    <a href="{{ route('patient.doctors.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-3 px-8 rounded-xl transition duration-150 shadow-md transform hover:scale-[1.01]">
                        Kembali Mencari
                    </a>
                    <button type="submit" class="bg-primary-blue hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl text-lg transition duration-150 shadow-xl transform hover:scale-[1.01]">
                        Konfirmasi & Lanjutkan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script JavaScript (TIDAK BERUBAH) --}}
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
