<x-layouts.patient-app>
    <x-slot name="header">
        {{ __('Pesan Janji Temu') }}
    </x-slot>

    <div class="space-y-8">

        {{-- CARD PROFIL DOKTER --}}
        <div class="bg-white rounded-xl p-6 card-shadow border-t-4 border-primary-blue">
            <div class="flex items-start space-x-6">

                {{-- Foto Dokter (Placeholder) --}}
                <div class="shrink-0">
                    <img src="https://i.pravatar.cc/150?img={{ $doctor->id }}" alt="{{ $doctor->user->name }}" class="w-24 h-24 object-cover rounded-full border-4 border-gray-100">
                </div>

                {{-- Detail Dokter --}}
                <div>
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-1">{{ $doctor->user->name }}</h3>
                    <p class="text-lg font-semibold text-primary-blue">{{ $doctor->specialty }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $doctor->bio }}</p>
                    <p class="text-xs text-gray-500 mt-1">Departemen: {{ $doctor->hospitalDepartment->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- JADWAL PRAKTIK MINGGUAN (Tabel Informasi) --}}
        <div class="bg-white rounded-xl p-6 card-shadow">
            <h4 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">Jadwal Praktik Mingguan</h4>

            @if(empty($schedules) || collect($schedules)->flatten()->isEmpty())
                <p class="text-red-500 font-semibold">⚠️ Dokter tidak memiliki jadwal praktik yang terdaftar.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Praktik</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kuota Pasien</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- Flatten the schedules array to list all entries --}}
                            @php $allSchedules = collect($schedules)->flatten(); @endphp
                            @foreach ($allSchedules as $schedule)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ $schedule->day_of_week }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-primary-blue font-mono">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $schedule->max_patients }} Pasien</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- AREA FORM PEMESANAN --}}
        <div class="bg-white rounded-xl p-6 card-shadow">

            <h4 class="text-xl font-bold mb-4 text-gray-800 border-b pb-2">Pesan Janji Temu</h4>

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
                    <small class="text-gray-500 mt-2 block">Pilih tanggal untuk melihat jam praktik yang tersedia.</small>
                </div>

                {{-- 2. Pilih Jam (Jadwal Tersedia) --}}
                <div>
                    <label for="schedule_id" class="block font-medium text-gray-800 mb-2">Pilih Jam</label>
                    <select id="schedule_id" name="schedule_id" required disabled
                            class="w-full border-gray-300 rounded-xl shadow-sm bg-gray-100 text-gray-500 focus:border-primary-blue focus:ring-primary-blue disabled:bg-gray-100 disabled:text-gray-500">
                        <option value="">Pilih tanggal terlebih dahulu</option>

                        {{-- Opsi Jadwal --}}
                        @foreach(collect($schedules)->flatten() as $schedule)
                            <option value="{{ $schedule->id }}"
                                    data-day="{{ $schedule->day_of_week }}"
                                    data-time="{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}"
                                    data-max-patients="{{ $schedule->max_patients }}"
                                    class="hidden">
                                {{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }} (Kuota: {{ $schedule->max_patients }})
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

    {{-- Script untuk memfilter jadwal dan menghitung antrian --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInput = document.getElementById('appointment_date');
            const scheduleSelect = document.getElementById('schedule_id');
            const placeholderOption = scheduleSelect.querySelector('option[value=""]');
            const scheduleOptions = Array.from(scheduleSelect.querySelectorAll('option:not([value=""])'));
            const noScheduleMessage = document.getElementById('no-schedule-message');
            const queueDisplay = document.getElementById('queue-display');
            const nextQueueNumberSpan = document.getElementById('next-queue-number');

            // Fungsi untuk mengkonversi tanggal ke nama hari dalam Bahasa Indonesia (sesuai seeder)
            function getDayName(dateString) {
                const date = new Date(dateString + 'T00:00:00');
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                return days[date.getDay()];
            }

            function filterSchedules() {
                const dateValue = dateInput.value;

                // --- A. Reset UI ---
                scheduleSelect.disabled = true;
                scheduleSelect.value = "";
                scheduleOptions.forEach(opt => opt.classList.add('hidden'));
                placeholderOption.textContent = "Pilih tanggal terlebih dahulu";
                noScheduleMessage.classList.add('hidden');
                queueDisplay.classList.add('hidden');
                scheduleSelect.classList.add('bg-gray-100', 'text-gray-500');

                if (!dateValue) {
                    return;
                }

                // --- B. Filter Opsi ---
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

                // --- C. Atur Status Akhir ---
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

                // Sembunyikan jika input tidak lengkap
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Ambil CSRF Token dari meta tag
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

            // --- LISTENERS ---
            dateInput.addEventListener('change', () => {
                filterSchedules();
                calculateQueueStatus();
            });
            scheduleSelect.addEventListener('change', calculateQueueStatus);

            // Inisialisasi filter saat halaman dimuat
            filterSchedules();
        });
    </script>
</x-layouts.patient-app>
